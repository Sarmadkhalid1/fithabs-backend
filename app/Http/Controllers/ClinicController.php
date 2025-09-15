<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ClinicController extends Controller
{
    public function index()
    {
        try {
            $clinics = Clinic::where('is_active', true)
                ->get()
                ->map(function($clinic) {
                    return [
                        'id' => $clinic->id,
                        'name' => $clinic->name,
                        'description' => $clinic->description,
                        'logo' => $clinic->logo,
                        'phone' => $clinic->phone,
                        'address' => $clinic->address,
                        'website' => $clinic->website,
                        'services' => $clinic->services,
                        'chat_url' => "/api/v1/clinics/{$clinic->id}/chat"
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $clinics,
                'count' => $clinics->count()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve clinics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function adminIndex(Request $request)
    {
        try {
            $query = Clinic::query();

            // Search functionality
            if ($request->has('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%")
                      ->orWhereJsonContains('services', $search);
                });
            }

            // Filter by service
            if ($request->has('service')) {
                $query->whereJsonContains('services', $request->get('service'));
            }

            // Filter by location
            if ($request->has('location')) {
                $query->where('address', 'like', "%{$request->get('location')}%");
            }

            // Filter by status
            if ($request->has('status')) {
                $status = $request->get('status');
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
                // 'all' shows both active and inactive
            } else {
                // Default to showing all for admin
                $query->where('is_active', true);
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 100);
            $clinics = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json($clinics, 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve clinics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $clinic = Clinic::findOrFail($id);
        return response()->json($clinic, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clinics',
            'password' => 'required|string|min:8',
            'description' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string|url',
            'services' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $logoUrl = null;
            
            // Handle image upload if provided
            if ($request->hasFile('logo')) {
                $logoUrl = $this->handleImageUpload($request->file('logo'), 'profile');
            } elseif ($request->has('logo') && is_string($request->logo)) {
                $logoUrl = $request->logo;
            }

            $clinic = Clinic::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'description' => $request->description,
                'logo' => $logoUrl,
                'phone' => $request->phone,
                'address' => $request->address,
                'website' => $request->website,
                'services' => $request->services,
                'is_active' => $request->is_active ?? true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Clinic created successfully',
                'data' => $clinic,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create clinic: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:clinics,email,'.$id,
            'password' => 'sometimes|string|min:8',
            'description' => 'nullable|string',
            'logo' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string|url',
            'services' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $request->only([
                'name', 'email', 'description', 'phone',
                'address', 'website', 'services', 'is_active'
            ]);

            // Handle image upload if provided
            if ($request->hasFile('logo')) {
                $data['logo'] = $this->handleImageUpload($request->file('logo'), 'profile');
            } elseif ($request->has('logo') && is_string($request->logo)) {
                $data['logo'] = $request->logo;
            }

            if ($request->has('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $clinic->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Clinic updated successfully',
                'data' => $clinic,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update clinic: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $clinic = Clinic::findOrFail($id);
            $clinic->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Clinic deleted successfully',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete clinic: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function handleImageUpload($file, $category = 'profile')
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        $filename = Str::uuid() . '.' . $extension;
        
        $path = $file->storeAs('', $filename, 'images');
        
        // Generate the correct URL based on the current request
        $baseUrl = request()->getSchemeAndHttpHost();
        $url = $baseUrl . '/storage/images/' . $filename;

        $imageInfo = getimagesize($file->getPathname());
        $width = $imageInfo[0] ?? null;
        $height = $imageInfo[1] ?? null;

        Image::create([
            'title' => $originalName,
            'description' => 'Uploaded image for ' . $category,
            'filename' => $originalName,
            'path' => $path,
            'url' => $url,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'category' => $category,
            'uploaded_by' => auth()->id(),
        ]);

        return $url;
    }
}