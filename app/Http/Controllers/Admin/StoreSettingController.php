<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreSettingController extends Controller
{
    public function edit()
    {
        $setting = StoreSetting::current();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name'     => 'required|string|max:255',
            'address'        => 'nullable|string',
            'phone'          => 'nullable|string|max:30',
            'receipt_footer' => 'nullable|string|max:255',
            'enable_tax'     => 'nullable|boolean',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'logo'           => 'nullable|image|max:1024',
        ]);

        $setting = StoreSetting::current();

        $validated['enable_tax'] = $request->boolean('enable_tax');

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('store', 'public');
        }

        $setting->update($validated);

        return back()->with('success', 'Pengaturan toko berhasil disimpan.');
    }
}