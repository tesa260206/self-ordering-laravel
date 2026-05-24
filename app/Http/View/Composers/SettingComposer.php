<?php

namespace App\Http\View\Composers;

use App\Models\Setting;
use Illuminate\View\View;

class SettingComposer
{
    protected $setting;

    public function __construct()
    {
        $this->setting = Setting::first();
        if (!$this->setting) {
            $this->setting = new Setting([
                'resto_name' => 'Self Order System',
                'phone' => null,
                'address' => null,
                'tax' => 0,
                'service_charge' => 0,
                'logo' => null,
                'thermal_printer_width' => '80mm',
                'receipt_footer_text' => 'Terima Kasih atas kunjungan Anda!',
            ]);
        }
    }

    public function compose(View $view): void
    {
        $view->with('globalSetting', $this->setting);
    }
}
