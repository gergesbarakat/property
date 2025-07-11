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
        return view('tenant.show', compact('tenant'));
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
                if ($request->hasFile('profile')) {
                    $profileImagePath = $request->file('profile')->store('upload/profiles', 'public');
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
                    $leaseEndDate = \Carbon\Carbon::parse($validatedData['installment_start_date']);
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
                        $path = $file->store('contracts', 'public');
                        Contract::create(['tenant_id' => $tenant->id, 'contract_file' => $path]);
                    }
                }

                $unit = PropertyUnit::findOrFail($validatedData['unit']);
                $unit->status = 'sold';
                $unit->save();

                // ✅ FIX: This entire block handles the creation of installment records.
                if ($validatedData['purchase_type'] === 'installment') {
                    $duration = (int) $validatedData['installment_duration'];
                    $feePercent = (float) $request->installment_fee_percent ?? 0;
                    $balance = $validatedData['unit_price'] - $validatedData['deposit'];
                    $totalFee = $balance * ($feePercent / 100);
                    $totalInstallmentAmount = $balance + $totalFee;
                    $amountPerInstallment = ($duration > 0) ? $totalInstallmentAmount / $duration : 0;
                    $currentDueDate = \Carbon\Carbon::parse($validatedData['installment_start_date']);

                    for ($i = 0; $i < $duration; $i++) {
                        Installment::create([
                            'buyer_id' => $tenant->id,
                            'unit_id' => $unit->id,
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
            $property = Property::where('is_active', 1)->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');
            $user = User::find($tenant->user_id);
            // Also fetch available units for the current property
            $units = PropertyUnit::where('property_id', $tenant->property)->where('status', '!=', 'sold')->get()->pluck('name', 'id');
            return view('tenant.edit', compact('property', 'tenant', 'user', 'units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function update(Request $request, Tenant $tenant)
    {
        if (\Auth::user()->can('edit tenant')) {
            $user = User::find($tenant->user_id);
            if (!$user) {
                return response()->json(['status' => 'error', 'msg' => 'Associated user not found.']);
            }

            $validator = \Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'phone_number' => 'required|string|max:20',
                'national_id' => 'nullable|string|max:255',
                // ... other rules ...
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => $validator->errors()->first()]);
            }

            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);

            if ($request->hasFile('profile')) {
                if ($user->profile) {
                    Storage::disk('public')->delete($user->profile);
                }
                $path = $request->file('profile')->store('profiles', 'public');
                $user->profile = $path;
                $user->save();
            }

            $tenant->update([
                'family_member' => $request->family_member,
                'national_id' => $request->national_id,
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address,
            ]);

            return response()->json(['status' => 'success', 'msg' => __('Tenant successfully updated.')]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
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
}
