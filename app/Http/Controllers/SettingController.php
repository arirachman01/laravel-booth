<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * GET /api/settings
     */
    public function index()
    {
        $setting = Setting::find(1);

        return view('dashboard', [
            'setting' => $setting,
        ]);
    }
    public function view()
    {
        $sessions = Setting::find(1);
        
        return response()->json([
            'success' => true,
            'data' => $sessions,
        ]);
    }
    /**
     * PUT/PATCH /api/settings/{id}
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'eyebrow' => 'nullable|string|max:255',
            'website_name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'website_description' => 'nullable|string',

            'photo_price' => 'required|integer|min:0',
            'photo_count' => 'required|integer|min:1',
            'countdown' => 'required|integer|min:0',
            'max_time' => 'required|integer|min:1',

            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'background_color' => 'required|string|max:20',
            'surface_color' => 'required|string|max:20',
            'text_color' => 'required|string|max:20',
            'accent_color' => 'required|string|max:20',
        ]);

        $setting = Setting::first();

        if ($setting) {
            $setting->update($validated);
        } else {
            Setting::create($validated);
        }

        return redirect()
            ->back()
            ->with('success', 'Pengaturan berhasil diperbarui.')
            ->withFragment('settingsSection');
    }
}