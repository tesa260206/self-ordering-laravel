<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['resto_name', 'phone', 'address', 'tax', 'service_charge', 'logo', 'thermal_printer_width', 'receipt_footer_text'];
}