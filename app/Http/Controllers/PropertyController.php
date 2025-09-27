<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyUnit;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // ✅ FIX: Added the missing import for the Storage facade
class PropertyController extends Controller
{

    public function index()
    {
        if (\Auth::user()->can('manage property')) {
            $properties = Property::where('is_active', 1)->get();
            return view('property.index', compact('properties'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function create()
    {
        if (\Auth::user()->can('create property')) {
            $types = Property::$Type;
            return view('property.create', compact('types'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function store(Request $request)
    {
        if (\Auth::user()->can('create property')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                    'type' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',
                    'thumbnail' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),

                ]);
            }

            $ids = parentId();
            $authUser = \App\Models\User::find($ids);
            // $totalProperty = $authUser->totalProperty();
            // $subscription = Subscription::find($authUser->subscription);
            // if ($totalProperty >= $subscription->property_limit && $subscription->property_limit != 0) {
            //     return response()->json([
            //         'status' => 'error',
            //         'msg' => __('Your property limit is over, please upgrade your subscription.'),
            //         'id' => 0,
            //     ]);
            // }
            $property = new Property();
            $property->name = $request->name;
            $property->description = $request->description;
            $property->type = $request->type;
            $property->country = $request->country;
            $property->state = $request->state;
            $property->city = $request->city;
            $property->zip_code = $request->zip_code;
            $property->address = $request->address;
            $property->parent_id = parentId();
            $property->save();

            if ($request->thumbnail != 'undefined') {
                $thumbnailFilenameWithExt = $request->file('thumbnail')->getClientOriginalName();
                $thumbnailFilename = pathinfo($thumbnailFilenameWithExt, PATHINFO_FILENAME);
                $thumbnailExtension = $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnailFileName = $thumbnailFilename . '_' . time() . '.' . $thumbnailExtension;
                $dir = storage_path('upload/thumbnail');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('thumbnail')->storeAs('upload/thumbnail/', $thumbnailFileName);
                $thumbnail = new PropertyImage();
                $thumbnail->property_id = $property->id;
                $thumbnail->image = $thumbnailFileName;
                $thumbnail->type = 'thumbnail';
                $thumbnail->save();
            }

            if (!empty($request->property_images)) {
                foreach ($request->property_images as $file) {
                    $propertyFilenameWithExt = $file->getClientOriginalName();
                    $propertyFilename = pathinfo($propertyFilenameWithExt, PATHINFO_FILENAME);
                    $propertyExtension = $file->getClientOriginalExtension();
                    $propertyFileName = $propertyFilename . '_' . time() . '.' . $propertyExtension;
                    $dir = storage_path('upload/property');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file->storeAs('upload/property/', $propertyFileName);

                    $propertyImage = new PropertyImage();
                    $propertyImage->property_id = $property->id;
                    $propertyImage->image = $propertyFileName;
                    $propertyImage->type = 'extra';
                    $propertyImage->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'msg' => __('Property successfully created.'),
                'id' => $property->id,
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function show(Property $property)
    {
        if (\Auth::user()->can('show property')) {
            $units = PropertyUnit::where('property_id', $property->id)->orderBy('id', 'desc')->with(['property'])->get();
            return view('property.show', compact('property', 'units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function edit(Property $property)
    {
        if (\Auth::user()->can('edit property')) {
            $types = Property::$Type;
            return view('property.edit', compact('types', 'property'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function update(Request $request, Property $property)
    {

        if (\Auth::user()->can('edit property')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'description' => 'required',
                    'type' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                    'zip_code' => 'required',
                    'address' => 'required',

                ]

            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return response()->json([
                    'status' => 'error',
                    'msg' => $messages->first(),

                ]);
            }

            $property->name = $request->name;
            $property->description = $request->description;
            $property->type = $request->type;
            $property->country = $request->country;
            $property->state = $request->state;
            $property->city = $request->city;
            $property->zip_code = $request->zip_code;
            $property->address = $request->address;
            $property->save();

            if (!empty($request->thumbnail)) {
                if (!empty($property->thumbnail) && isset($property->thumbnail->image)) {
                    $image_path = "storage/upload/thumbnail/" . $property->thumbnail->image;
                    if (\File::exists($image_path)) {
                        \File::delete($image_path);
                    }
                }

                $thumbnailFilenameWithExt = $request->file('thumbnail')->getClientOriginalName();
                $thumbnailFilename = pathinfo($thumbnailFilenameWithExt, PATHINFO_FILENAME);
                $thumbnailExtension = $request->file('thumbnail')->getClientOriginalExtension();
                $thumbnailFileName = $thumbnailFilename . '_' . time() . '.' . $thumbnailExtension;
                $dir = storage_path('upload/thumbnail');
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $request->file('thumbnail')->storeAs('upload/thumbnail/', $thumbnailFileName);
                $thumbnail = PropertyImage::where('property_id', $property->id)->where('type', 'thumbnail')->first();
                $thumbnail->image = $thumbnailFileName;
                $thumbnail->save();
            }

            if (!empty($request->property_images)) {
                foreach ($request->property_images as $file) {
                    $propertyFilenameWithExt = $file->getClientOriginalName();
                    $propertyFilename = pathinfo($propertyFilenameWithExt, PATHINFO_FILENAME);
                    $propertyExtension = $file->getClientOriginalExtension();
                    $propertyFileName = $propertyFilename . '_' . time() . '.' . $propertyExtension;
                    $dir = storage_path('upload/property');
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file->storeAs('upload/property/', $propertyFileName);

                    $propertyImage = new PropertyImage();
                    $propertyImage->property_id = $property->id;
                    $propertyImage->image = $propertyFileName;
                    $propertyImage->type = 'extra';
                    $propertyImage->save();
                }
            }

            return response()->json([
                'status' => 'success',
                'msg' => __('Property successfully updated.'),
                'id' => $property->id,
            ]);
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function destroyImage(PropertyImage $image)
    {
        if (\Auth::user()->can('edit property')) {
            try {
                // The 'image' column stores the filename only, so we need to build the full path.
                $filePath = 'upload/property/' . $image->image;
                if ($image->type == 'thumbnail') {
                    $filePath = 'upload/thumbnail/' . $image->image;
                }

                // Delete the physical file from storage.
                Storage::delete($filePath);

                // Delete the record from the database.
                $image->delete();

                return redirect()->back()->with('success', 'Image successfully deleted.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to delete image.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function destroy(Property $property)
    {
        if (\Auth::user()->can('delete property')) {
            // First, check if any units within this property are sold.
            $hasSoldUnits = $property->totalUnits()->where('status', 'sold')->exists();

            if ($hasSoldUnits) {
                return redirect()->back()->with('error', __('This property cannot be deactivated because it has sold units.'));
            }

            try {
                // Check if the property is already inactive.
                if ($property->is_active == 0) {
                    return redirect()->back()->with('warning', 'This property is already deactivated.');
                }

                // Set the property to inactive instead of deleting.
                $property->is_active = 0;
                $property->save();

                return redirect()->back()->with('success', 'Property successfully deactivated.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to deactivate property. Please try again.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }


    public function units()
    {
        if (\Auth::user()->can('manage unit')) {
            $units = PropertyUnit::with(['property'])->whereHas('property', function ($q) {
                $q->where('is_active', 1);
            })->get();
            return view('unit.index', compact('units'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
    public function unitCreate($pid)
    {
        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;
        $property_id = $pid;
        return view('unit.create', compact('types', 'property_id', 'rentTypes'));
    }

    public function unitStore(Request $request, $property_id)
    {
        if (\Auth::user()->can('create unit')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:property_units,name,NULL,id,property_id,' . $property_id,
                'bedroom' => 'nullable|integer|min:0',
                'kitchen' => 'nullable|integer|min:0',
                'baths' => 'nullable|integer|min:0',
                'unit_size' => 'nullable|integer|min:0',
                'floor' => 'nullable|string|max:255',
                'building' => 'nullable|string|max:255',
                'location' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $data = $validator->validated();

            PropertyUnit::create([
                'name' => $data['name'],
                'bedroom' => empty($data['bedroom']) ? 0 : $data['bedroom'],
                'kitchen' => empty($data['kitchen']) ? 0 : $data['kitchen'],
                'baths' => empty($data['baths']) ? 0 : $data['baths'],
                'unit_size' => empty($data['unit_size']) ? null : $data['unit_size'],
                'floor' => empty($data['floor']) ? null : $data['floor'],
                'building' => empty($data['building']) ? null : $data['building'],
                'location' => empty($data['location']) ? null : $data['location'],
                'notes' => empty($data['notes']) ? null : $data['notes'],

                'property_id' => $property_id,
                'parent_id' => parentId(),
                'status' => 'available',
            ]);

            return redirect()->back()->with('success', __('Unit successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }
    // ✅ FIX: Changed to find models manually instead of route-model binding.
    public function unitEdit($pid, $id)
    {
        $unit = PropertyUnit::find($id);
        if (!$unit) {
            return response('Unit not found.', 404);
        }
        $types = PropertyUnit::$Types;
        $rentTypes = PropertyUnit::$rentTypes;
        $property_id = $pid; // Pass the property ID to the view
        return view('unit.edit', compact('types', 'property_id', 'rentTypes', 'unit'));
    }

    // ✅ FIX: Changed to find model manually instead of route-model binding.
    public function unitUpdate(Request $request, $pid, $id)
    {
        if (\Auth::user()->can('edit unit')) {
            $unit = PropertyUnit::find($id);
            if (!$unit) {
                return redirect()->back()->with('error', __('Unit not found.'));
            }

            if (strtolower($unit->status) === 'sold') {
                return redirect()->back()->with('error', __('A sold unit cannot be edited.'));
            }

            $validator = Validator::make($request->all(), ['name' => 'required']);
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $unit->update($request->all());

            if (strtolower($unit->status) == 'deactivated') {
                $unit->status = 'available';
                $unit->save();
            }

            return redirect()->back()->with('success', __('Unit successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    // ✅ FIX: Changed to find model manually instead of route-model binding.
    public function unitDestroy($pid, $id)
    {
        if (\Auth::user()->can('delete unit')) {
            $unit = PropertyUnit::find($id);
            if (!$unit) {
                return redirect()->back()->with('error', 'Unit not found.');
            }

            if (strtolower($unit->status) !== 'available') {
                return redirect()->back()->with('error', __('Only available units can be deactivated. This unit is either sold or already inactive.'));
            }

            try {
                $unit->status = 'deactivated';
                $unit->save();
                return redirect()->back()->with('success', 'Unit successfully deactivated.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to deactivate unit. Please try again.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied!'));
        }
    }

    public function getPropertyUnit($pid)
    {
        $units = PropertyUnit::where('property_id', $pid)->where('status', 'available')->get()->pluck('name', 'id');
        return response()->json($units);
    }
}
