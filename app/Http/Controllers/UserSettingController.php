<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSetting;

class UserSettingController extends Controller
{
    public function index()
    {
        $settings = UserSetting::where('user_id', auth()->id())->get();
        return response()->json($settings, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required|string',
        ]);

        $validated['user_id'] = auth()->id();
        
        $setting = UserSetting::create($validated);
        return response()->json($setting, 201);
    }

    public function show($id)
    {
        $setting = UserSetting::where('user_id', auth()->id())
            ->findOrFail($id);
        return response()->json($setting, 200);
    }

    public function update(Request $request, $id)
    {
        $setting = UserSetting::where('user_id', auth()->id())
            ->findOrFail($id);

        $validated = $request->validate([
            'value' => 'required|string',
        ]);

        $setting->update($validated);
        return response()->json($setting, 200);
    }

    public function destroy($id)
    {
        $setting = UserSetting::where('user_id', auth()->id())
            ->findOrFail($id);
        $setting->delete();
        return response()->json(null, 204);
    }
}
