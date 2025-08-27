<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    public function index()
    {
        return response()->json(AdminUser::all(), 200);
    }

    public function show($id)
    {
        $admin = AdminUser::findOrFail($id);
        return response()->json($admin, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin_users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,admin,editor',
            'permissions' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $admin = AdminUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'permissions' => $request->permissions,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json($admin, 201);
    }

    public function update(Request $request, $id)
    {
        $admin = AdminUser::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:admin_users,email,'.$id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:super_admin,admin,editor',
            'permissions' => 'nullable|array',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['name', 'email', 'role', 'permissions', 'is_active']);
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);
        return response()->json($admin, 200);
    }

    public function destroy($id)
    {
        $admin = AdminUser::findOrFail($id);
        $admin->delete();
        return response()->json(null, 204);
    }
}