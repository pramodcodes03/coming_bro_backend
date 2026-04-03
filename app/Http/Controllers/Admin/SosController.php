<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SosController extends Controller
{
    public function index()
    {
        $contacts = [];

        $setting = Setting::where('key', 'sos_contacts')->first();

        if ($setting) {
            $contacts = is_array($setting->value) ? $setting->value : json_decode($setting->value, true) ?? [];
        }

        return view('admin.sos.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contacts'          => 'required|array|min:1',
            'contacts.*.name'   => 'required|string|max:255',
            'contacts.*.number' => 'required|string|max:20',
        ]);

        $setting = Setting::firstOrCreate(
            ['key' => 'sos_contacts'],
            ['value' => []]
        );

        $setting->value = $validated['contacts'];
        $setting->save();

        return redirect()->route('admin.sos.index')
            ->with('success', 'SOS contacts updated successfully.');
    }
}
