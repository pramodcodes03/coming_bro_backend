<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();

        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request, string $key)
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        $value = $request->input('value');

        // If value is an array (from form fields), process each field
        if (is_array($value)) {
            $existingValue = $setting->value ?? [];

            foreach ($value as $field => $val) {
                // Try to decode JSON strings back to arrays
                $decoded = json_decode($val, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $existingValue[$field] = $decoded;
                } else {
                    $existingValue[$field] = $val;
                }
            }

            $setting->value = $existingValue;
        } else {
            // Single value - try JSON decode first
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $setting->value = $decoded;
            } else {
                $setting->value = $value;
            }
        }

        $setting->save();

        return redirect()->route('admin.settings.index')
            ->with('success', ucwords(str_replace(['_', '-'], ' ', $key)) . ' updated successfully.');
    }
}
