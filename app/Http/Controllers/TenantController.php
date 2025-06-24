<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\TenantDocument;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Installment;
use App\Models\PropertyUnit;
use App\Models\Invoice;
use App\Models\Contract;

use App\Models\InvoicePayment;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // ✅ FIX: Added the missing import for the Storage facade.


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TenantController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage tenant')) {
            $tenants = Tenant::with([
                'user',
                'linked_property', // ✅ Use the new relationship name
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
        // ✅ FIX: Add 'contracts' to the list of relationships to load.
        $tenant->load(['user', 'linked_property', 'propertyUnit', 'installments', 'contracts']);

        return view('tenant.show', compact('tenant'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */    public function create()
    {
        if (\Auth::user()->can('create tenant')) {
            $property = Property::get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);
            return view('tenant.create', compact('property'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
    public function store(Request $request)
    {
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
                'installment_type' => 'required_if:purchase_type,installment|in:monthly,yearly',
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
                $tenantFilenameWithExt = $request->profile->getClientOriginalName();
                $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                $tenantExtension = $request->profile->getClientOriginalExtension();
                $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;

                // Store using the public disk and get the full path
                $profileImagePath = $request->profile->storeAs('upload/profiles', $tenantFileName, 'public');

                // Alternative: if you want to keep your current approach, store the relative path
                $profileImagePath = 'upload/profiles/' . $tenantFileName;
            }

            // The rest of your user creation code remains the same
            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'phone_number' => $validatedData['phone_number'],
                'profile' => $profileImagePath, // This will now be the full path
                'type' => 'tenant',
                'is_active' => 1,
            ]);
            $tenant = Tenant::create([
                'user_id' => $user->id,
                'family_member' => $validatedData['family_member'],
                'address' => $validatedData['address'],
                'country' => $validatedData['country'],
                'state' => $validatedData['state'],
                'city' => $validatedData['city'],
                'zip_code' => $validatedData['zip_code'],
                'property' => $validatedData['property'],
                'unit' => $validatedData['unit'],
                'purchase_type' => $validatedData['purchase_type'],

                'lease_start_date' => $validatedData['installment_start_date'],
                'lease_end_date' =>  date('Y-m-d', strtotime("+" . $validatedData['installment_duration'] . " months", strtotime($validatedData['installment_start_date']))),

                'email' => $user->email,
                'phone' => $user->phone_number,
                'profile_image' => $user->profile,
            ]);

            if ($request->hasFile('tenant_images')) {
                foreach ($request->file('tenant_images') as $file) {
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $path = $file->storeAs('tenant_images', $fileNameToStore, 'public');
                    Contract::create(['tenant_id' => $tenant->id, 'contract_file' => $path]);
                }
            }

            $unit = PropertyUnit::findOrFail($validatedData['unit']);
            $unit->status = 'sold';
            $unit->save();

            if ($validatedData['purchase_type'] === 'full') {
                // Create single installment for full payment
                $installment = Installment::create([
                    'buyer_id' => $tenant->id,
                    'installment_number' => 1,
                    'due_date' => $validatedData['payment_date'],
                    'amount' => $validatedData['unit_price'],
                    'status' => 'paid',
                    'paid_date' => $validatedData['payment_date'],
                    'notes' => 'Full payment for unit purchase',
                ]);

                // Generate unique invoice ID
                $invoiceId = 'INV-' . date('Ymd') . '-' . str_pad($tenant->id, 4, '0', STR_PAD_LEFT);

                // Create invoice for full payment
                $invoice = Invoice::create([
                    'invoice_id' => $invoiceId,
                    'property_id' => $validatedData['property'],
                    'unit_id' => $validatedData['unit'],
                    'invoice_month' => date('Y-m-01', strtotime($validatedData['payment_date'])),
                    'end_date' => $validatedData['payment_date'],
                    'status' => 'paid',
                    'notes' => 'Full payment invoice for unit purchase',
                    'parent_id' => 0,
                ]);

                // Create invoice payment record
                InvoicePayment::create([
                    'invoice_id' => $invoice->id,
                    'transaction_id' => 'TXN-' . time() . '-' . $tenant->id,
                    'payment_type' => 'full_payment',
                    'amount' => $validatedData['unit_price'],
                    'payment_date' => $validatedData['payment_date'],
                    'parent_id' => 0,
                    'notes' => 'Full payment for unit purchase',
                ]);
            } elseif ($validatedData['purchase_type'] === 'installment') {
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
                        'installment_number' => $i + 1,
                        'due_date' => $currentDueDate->format('Y-m-d'),
                        'amount' => round($amountPerInstallment, 2),
                        'status' => 'pending',
                    ]);
                    if ($validatedData['installment_type'] === 'monthly') {
                        $currentDueDate->addMonth();
                    } else {
                        $currentDueDate->addYear();
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

    // public function show(Tenant $tenant)
    // {
    //     if (\Auth::user()->can('show tenant')) {
    //         return view('tenant.show', compact('tenant'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission Denied!'));
    //     }
    // }

    public function edit(Tenant $tenant)
    {
        if (\Auth::user()->can('edit tenant')) {
            $property = Property::get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), 0);

            $user = User::find($tenant->user_id);
            return view('tenant.edit', compact('property', 'tenant', 'user'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function update(Request $request, Tenant $tenant)
    {
        if (\Auth::user()->can('edit tenant')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required',
                    'phone_number' => 'required',
                    'family_member' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                    'property' => 'required',
                    'unit' => 'required',
                    'lease_start_date' => 'required',
                    'lease_end_date' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),

                ]);
            }

            $user = User::find($tenant->user_id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->save();

            if ($request->profile != '') {
                $tenantFilenameWithExt = $request->file('profile')->getClientOriginalName();
                $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                $tenantExtension = $request->file('profile')->getClientOriginalExtension();
                $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                $dir = storage_path('upload/profile');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('profile')->storeAs('upload/profile/', $tenantFileName);
                $user->profile = $tenantFileName;
                $user->save();
            }

            $tenant->family_member = $request->family_member;
            $tenant->country = $request->country;
            $tenant->state = $request->state;
            $tenant->city = $request->city;
            $tenant->zip_code = $request->zip_code;
            $tenant->address = $request->address;
            $tenant->property = $request->property;
            $tenant->unit = $request->unit;
            $tenant->lease_start_date = $request->lease_start_date;
            $tenant->lease_end_date = $request->lease_end_date;
            $tenant->save();



            if (!empty($request->tenant_images)) {
                foreach ($request->tenant_images as $file) {
                    $tenantFilenameWithExt = $file->getClientOriginalName();
                    $tenantFilename = pathinfo($tenantFilenameWithExt, PATHINFO_FILENAME);
                    $tenantExtension = $file->getClientOriginalExtension();
                    $tenantFileName = $tenantFilename . '_' . time() . '.' . $tenantExtension;
                    $dir = storage_path('upload/tenant');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file->storeAs('upload/tenant/', $tenantFileName);

                    $tenantImage = new TenantDocument();
                    $tenantImage->property_id = $request->property;
                    $tenantImage->tenant_id = $tenant->id;
                    $tenantImage->document = $tenantFileName;
                    $tenantImage->parent_id = parentId();
                    $tenantImage->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'msg' => __('Tenant successfully updated.'),
            ]);
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
        $zipPath = storage_path("app/public/temp/{$zipFileName}");

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($tenant->contracts as $contract) {
                $filePath = storage_path('app/public/' . $contract->contract_file);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        } else {
            return back()->with('error', 'Could not create ZIP file.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }





    public function downloadContracts(Tenant $tenant)
    {
        // Ensure the contracts relationship is loaded
        $tenant->load('contracts', 'user');

        // Check if there are any contracts to download
        if ($tenant->contracts->isEmpty()) {
            return redirect()->back()->with('error', 'This tenant has no documents to download.');
        }

        // Create a unique name for the zip file
        $zipFileName = 'contracts-' . str_replace(' ', '_', optional($tenant->user)->first_name) . '.zip';

        // ✅ FIX: Call ZipArchive from the global namespace using a preceding backslash.
        $zip = new \ZipArchive();

        // Create a temporary file path for the zip archive
        $tempFilePath = tempnam(sys_get_temp_dir(), 'zip');

        if ($zip->open($tempFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {

            // Loop through each contract document
            foreach ($tenant->contracts as $contract) {
                // Use the Storage facade to get the file path safely
                $filePath = Storage::disk('public')->path($contract->contract_file);

                // Check if the file actually exists before adding it
                if (file_exists($filePath)) {
                    // Add the file to the zip archive, giving it its original name
                    $zip->addFile($filePath, basename($contract->contract_file));
                }
            }

            // Close the zip archive
            $zip->close();

            // Return the zip file as a download and delete the temporary file after it's sent
            return response()->download($tempFilePath, $zipFileName)->deleteFileAfterSend(true);
        } else {
            return redirect()->back()->with('error', 'Could not create the zip archive.');
        }
    }
}
