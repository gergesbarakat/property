<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Installment;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use ZipArchive;
use App\Models\InvoiceItem;
use App\Models\InvoicePayment;

class TenantController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage tenant')) {
            $tenants = Tenant::with([
                'user',
                'linked_property',
                'propertyUnit',
                'installments'
            ])->latest()->get();

            return view('tenant.index', compact('tenants'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['user', 'linked_property', 'propertyUnit', 'installments', 'contracts']);

        // ✅ NEW: Calculate financial summary for the tenant
        $allInstallments = $tenant->installments;
        $paidInstallments = $allInstallments->where('status', 'paid');

        $financialSummary = [
            'total_amount' => $allInstallments->sum('amount'),
            'paid_amount' => $paidInstallments->sum('amount'),
            'due_amount' => $allInstallments->sum('amount') - $paidInstallments->sum('amount'),
            'total_installments' => $allInstallments->count(),
            'paid_installments' => $paidInstallments->count(),
            'due_installments' => $allInstallments->where('status', '!=', 'paid')->count(),
        ];

        // Pass both the tenant and the financial summary to the view
        return view('tenant.show', compact('tenant', 'financialSummary'));
    }

    public function create()
    {
        if (\Auth::user()->can('create tenant')) {
            $property = Property::where('is_active', 1)->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');

            $units = []; // Units will be loaded via AJAX

            return view('tenant.create', compact('property', 'units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function getUnits(Request $request)
    {
        $request->validate(['property_id' => 'required|exists:properties,id']);
        $units = PropertyUnit::where('property_id', $request->property_id)
            ->where('status', 'available')
            ->get()->pluck('name', 'id');
        return response()->json($units);
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create tenant')) {
            if ($request->purchase_type === 'full' && !$request->filled('payment_date')) {
                $request->merge(['payment_date' => now()->format('Y-m-d')]);
            }

            try {
                $validatedData = $request->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:8',
                    'phone_number' => 'required|string|max:20',
                    'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'family_member' => 'nullable|integer|min:0',
                    'national_id' => 'nullable|string|max:255',
                    'country' => 'required|string|max:255',
                    'state' => 'required|string|max:255',
                    'city' => 'required|string|max:255',
                    'zip_code' => 'required|string|max:20',
                    'address' => 'required|string',
                    'property' => 'required|exists:properties,id',
                    'unit' => 'required|exists:property_units,id',
                    'unit_price' => 'required|numeric|min:0',
                    'purchase_type' => 'required|in:full,installment',
                    'payment_date' => 'required_if:purchase_type,full|date',
                    'installment_type' => 'required_if:purchase_type,installment|in:monthly,quarter_year,half_year,yearly',
                    'installment_duration' => 'required_if:purchase_type,installment|integer|min:1',
                    'installment_start_date' => 'required_if:purchase_type,installment|date',
                    'deposit' => 'required_if:purchase_type,installment|numeric|min:0|lte:unit_price',
                    'contracts' => 'nullable|array',
                    'contracts.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:5120',
                ]);
            } catch (ValidationException $e) {
                return response()->json(['status' => 'error', 'msg' => $e->validator->errors()->first()], 422);
            }

            DB::beginTransaction();
            try {
                $profileImagePath = null;
                // ✅ FIX: Changed file handling to use storeAs for consistency.
                if ($request->hasFile('profile')) {
                    $file = $request->file('profile');
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                    // This stores the file in `storage/app/public/upload/profiles` and returns the full path.
                    $profileImagePath = $fileNameToStore;
                    $request->file('profile')->storeAs('upload/profiles/', $fileNameToStore, 'public');
                }

                $user = User::create([
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'phone_number' => $validatedData['phone_number'],
                    'profile' => $profileImagePath,
                    'type' => 'tenant',
                    'is_active' => 1,
                ]);

                $leaseEndDate = null;
                if ($validatedData['purchase_type'] === 'installment') {
                    $leaseEndDate = Carbon::parse($validatedData['installment_start_date']);
                    $duration = (int) $validatedData['installment_duration'];
                    $monthsToAdd = match ($validatedData['installment_type']) {
                        'quarter_year' => $duration * 3,
                        'half_year' => $duration * 6,
                        'yearly' => $duration * 12,
                        default => $duration,
                    };
                    $leaseEndDate->addMonths($monthsToAdd);
                    $leaseEndDate = $leaseEndDate->format('Y-m-d');
                }

                $tenant = Tenant::create([
                    'user_id' => $user->id,
                    'family_member' => $validatedData['family_member'] ?? null,
                    'national_id' => $validatedData['national_id'] ?? null,
                    'address' => $validatedData['address'],
                    'country' => $validatedData['country'],
                    'state' => $validatedData['state'],
                    'city' => $validatedData['city'],
                    'zip_code' => $validatedData['zip_code'],
                    'property' => $validatedData['property'],
                    'unit' => $validatedData['unit'],
                    'purchase_type' => $validatedData['purchase_type'],
                    'lease_start_date' => $validatedData['installment_start_date'] ?? null,
                    'lease_end_date' =>  $leaseEndDate,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                    'profile_image' => $user->profile,
                ]);

                if ($request->hasFile('contracts')) {
                    foreach ($request->file('contracts') as $file) {
                        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        $path = $file->storeAs('contracts', $fileNameToStore, 'public');
                        Contract::create(['tenant_id' => $tenant->id, 'contract_file' => $path]);
                    }
                }

                $unit = PropertyUnit::findOrFail($validatedData['unit']);
                $unit->status = 'sold';
                $unit->save();

                if ($validatedData['purchase_type'] === 'installment') {
                    $duration = (int) $validatedData['installment_duration'];
                    $feePercent = (float) $request->installment_fee_percent ?? 0;
                    $balance = $validatedData['unit_price'] - $validatedData['deposit'];
                    $totalFee = $balance * ($feePercent / 100);
                    $totalInstallmentAmount = $balance + $totalFee;
                    $amountPerInstallment = ($duration > 0) ? $totalInstallmentAmount / $duration : 0;
                    $currentDueDate = Carbon::parse($validatedData['installment_start_date']);

                    for ($i = 0; $i < $duration; $i++) {
                        Installment::create([
                            'buyer_id' => $tenant->id,
                            'installment_number' => $i + 1,
                            'due_date' => $currentDueDate->format('Y-m-d'),
                            'amount' => round($amountPerInstallment, 2),
                            'status' => 'pending',
                        ]);

                        switch ($validatedData['installment_type']) {
                            case 'quarter_year':
                                $currentDueDate->addMonths(3);
                                break;
                            case 'half_year':
                                $currentDueDate->addMonths(6);
                                break;
                            case 'yearly':
                                $currentDueDate->addYear();
                                break;
                            default:
                                $currentDueDate->addMonth();
                                break;
                        }
                    }
                }

                DB::commit();
                return response()->json(['status' => 'success', 'msg' => __('Tenant successfully created.')]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Tenant Creation Failed: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'msg' => $e->getMessage()], 500);
            }
        }
        return redirect()->back()->with('error', __('Permission Denied!'));
    }



    public function edit(Tenant $tenant)
    {
        if (\Auth::user()->can('edit tenant')) {
            // Load the necessary relationships to display their names as text in the view.
            $tenant->load(['user', 'linked_property', 'propertyUnit']);
            $user = $tenant->user;

            // No longer passing $property or $units collections.
            return view('tenant.edit', compact('tenant', 'user'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tenant  $tenant
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Tenant $tenant)
    {
        if (\Auth::user()->can('edit tenant')) {
            $user = $tenant->user;
            if (!$user) {
                return response()->json(['status' => 'error', 'msg' => 'Associated user not found.'], 404);
            }

            // --- Validation ---
            // Note: 'property' and 'unit' are no longer validated as they are not submitted.
            $validator = \Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_number' => 'required|string|max:20',
                'profile' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'family_member' => 'nullable|integer|min:0',
                'national_id' => 'nullable|string|max:255',
                'country' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'zip_code' => 'required|string|max:20',
                'address' => 'required|string',
                'contracts' => 'nullable|array',
                'contracts.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:5120',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => $validator->errors()->first()], 422);
            }

            DB::beginTransaction();
            try {
                // --- Update User Details ---
                $user->first_name = $request->first_name;
                $user->last_name = $request->last_name;
                $user->email = $request->email;
                $user->phone_number = $request->phone_number;

                if ($request->hasFile('profile')) {
                    if ($user->profile) {
                        Storage::disk('public')->delete($user->profile);
                    }
                    $path = $request->file('profile')->store('profiles', 'public');
                    $user->profile = $path;
                }
                $user->save();

                // --- Update Tenant Details ---
                $tenant->family_member = $request->family_member;
                $tenant->national_id = $request->national_id;
                $tenant->country = $request->country;
                $tenant->state = $request->state;
                $tenant->city = $request->city;
                $tenant->zip_code = $request->zip_code;
                $tenant->address = $request->address;
                $tenant->email = $user->email;
                $tenant->phone = $user->phone_number;
                $tenant->profile_image = $user->profile;
                $tenant->save();

                // --- Handle New Contract Documents ---
                if ($request->hasFile('contracts')) {
                    foreach ($request->file('contracts') as $file) {
                        $path = $file->store('contracts', 'public');
                        Contract::create(['tenant_id' => $tenant->id, 'contract_file' => $path]);
                    }
                }

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'msg' => __('Tenant successfully updated.'),
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Tenant Update Failed: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'msg' => $e->getMessage()], 500);
            }
        }
        return redirect()->back()->with('error', __('Permission Denied!'));
    }





    public function destroy(Tenant $tenant)
    {
        if (\Auth::user()->can('delete tenant')) {
            $tenant->delete();
            return redirect()->back()->with('success', 'Tenant successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function downloadAllContracts($tenantId)
    {
        $tenant = Tenant::with('contracts')->findOrFail($tenantId);
        if ($tenant->contracts->isEmpty()) {
            return back()->with('error', 'No documents available.');
        }
        $zipFileName = 'contracts_' . $tenant->id . '.zip';
        $zip = new \ZipArchive;
        $tempFilePath = tempnam(sys_get_temp_dir(), 'zip');

        if ($zip->open($tempFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            foreach ($tenant->contracts as $contract) {
                $filePath = Storage::disk('public')->path($contract->contract_file);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'Could not create ZIP file.');
        }
        return response()->download($tempFilePath, $zipFileName)->deleteFileAfterSend(true);
    }
    public function destroyContract(Contract $contract)
    {
        // Ensure the user has permission to edit the parent tenant
        if (\Auth::user()->can('edit tenant')) {
            try {
                // Delete the physical file from storage
                if ($contract->contract_file) {
                    Storage::disk('public')->delete($contract->contract_file);
                }

                // Delete the database record
                $contract->delete();

                return redirect()->back()->with('success', 'Document successfully deleted.');
            } catch (\Exception $e) {
                Log::error('Contract Deletion Failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to delete the document.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
}
