<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = Setting::create([
                'resto_name'            => 'Self Order System',
                'tax'                   => 11,
                'service_charge'        => 5,
                'thermal_printer_width' => '80mm',
                'receipt_footer_text'   => 'Terima Kasih atas kunjungan Anda!',
            ]);
        }

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::first();

        // Antisipasi input koma dari browser locale Indonesia (11,50 -> 11.50)
        $request->merge([
            'tax' => str_replace(',', '.', $request->tax),
            'service_charge' => str_replace(',', '.', $request->service_charge),
        ]);

        $request->validate([
            'resto_name'            => 'required|string|max:255',
            'phone'                 => 'nullable|string|max:20',
            'address'               => 'nullable|string',
            'tax'                   => 'required|numeric|min:0|max:100',
            'service_charge'        => 'required|numeric|min:0|max:100',
            'logo'                  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'thermal_printer_width' => 'required|in:58mm,80mm',
            'receipt_footer_text'   => 'nullable|string|max:500',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $data['logo'] = $request->file('logo')->store('settings', 'public');
        }

        $setting->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan!'
        ]);
    }
}