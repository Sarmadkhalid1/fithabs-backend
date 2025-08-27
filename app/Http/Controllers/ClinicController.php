<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClinicController extends Controller
{
    public function index()
    {
        return response()->json(Clinic::all(), 200);
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
            'logo' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string|url',
            'services' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $clinic = Clinic::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'description' => $request->description,
            'logo' => $request->logo,
            'phone' => $request->phone,
            'address' => $request->address,
            'website' => $request->website,
            'services' => $request->services,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json($clinic, 201);
    }

    public function update(Request $request, $id)
    {
        $clinic = Clinic::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:clinics,email,'.$id,
            'password' => 'sometimes|string|min:8',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'website' => 'nullable|string|url',
            'services' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only([
            'name', 'email', 'description', 'logo', 'phone',
            'address', 'website', 'services', 'is_active'
        ]);
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $clinic->update($data);
        return response()->json($clinic, 200);
    }

    public function destroy($id)
    {
        $clinic = Clinic::findOrFail($id);
        $clinic->delete();
        return response()->json(null, 204);
    }
}