<?php

namespace App\Http\Controllers;

use App\Models\Therapist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class TherapistController extends Controller
{
    public function index()
    {
        return response()->json(Therapist::all(), 200);
    }

    public function show($id)
    {
        $therapist = Therapist::findOrFail($id);
        return response()->json($therapist, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:therapists',
            'password' => 'required|string|min:8',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|string',
            'specializations' => 'nullable|array',
            'certifications' => 'nullable|array',
            'phone' => 'nullable|string',
            'clinic_id' => 'nullable|exists:clinics,id',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $therapist = Therapist::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio,
            'profile_image' => $request->profile_image,
            'specializations' => $request->specializations,
            'certifications' => $request->certifications,
            'phone' => $request->phone,
            'clinic_id' => $request->clinic_id,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json($therapist, 201);
    }

    public function update(Request $request, $id)
    {
        $therapist = Therapist::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:therapists,email,'.$id,
            'password' => 'sometimes|string|min:8',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|string',
            'specializations' => 'nullable|array',
            'certifications' => 'nullable|array',
            'phone' => 'nullable|string',
            'clinic_id' => 'nullable|exists:clinics,id',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only([
            'name', 'email', 'bio', 'profile_image', 'specializations',
            'certifications', 'phone', 'clinic_id', 'is_active'
        ]);
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $therapist->update($data);
        return response()->json($therapist, 200);
    }

    public function destroy($id)
    {
        $therapist = Therapist::findOrFail($id);
        $therapist->delete();
        return response()->json(null, 204);
    }
}