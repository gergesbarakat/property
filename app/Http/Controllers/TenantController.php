<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\TenantDocument;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Installment;
use App\Models\PropertyUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
            // 1. Fetch all properties for the property dropdown.
            // ✅ CORRECTED: Changed variable name to $property to match the view.
            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
            $property->prepend(__('Select Property'), '');

            // 2. Fetch all units where the status is NOT 'sold'.
            // This collection will populate the unit dropdown.
            $units = PropertyUnit::where('status', '!=', 'sold')
                ->get()
                ->pluck('name', 'id');
            $units->prepend(__('Select Unit'), '');

            // 3. Pass both collections to the view.
            // ✅ CORRECTED: Updated compact() to send the correct variable name.
            return view('tenant.create', compact('property', 'units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    // ... your other methods (store, index, etc.)

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
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
            // ✅ FIX: Return the validation error as a plain string instead of a JSON object.
            return response()->json([
                'status' => 'error',
                'msg'    => $e->validator->errors()->first(), // The main error message string.
                'errors' => $e->validator->errors()->all() // The full array of errors.
            ], 422);
        }

        DB::beginTransaction();
        try {
            // ... (file upload and user creation logic is the same) ...
            $profileImagePath = null;
            if ($request->hasFile('profile')) {
                $file = $request->file('profile');
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $path = $file->storeAs('profiles', $fileNameToStore, 'public');
                $profileImagePath = $path;
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
                        'unit_id' => $unit->id,
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

            // The success response can remain a JSON object.
            return response()->json([
                'status' => 'success',
                'msg' => __('Tenant successfully created.'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Tenant Creation Failed: ' . $e->getMessage());

            // ✅ FIX: Return the general error as a plain string instead of a JSON object.
            return response($e->getMessage(), 500);
        }
    }


    public function edit(Tenant $tenant)
    {
        if (\Auth::user()->can('edit tenant')) {
            $property = Property::where('parent_id', parentId())->get()->pluck('name', 'id');
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
}
