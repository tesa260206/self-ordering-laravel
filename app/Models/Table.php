<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = [
        'table_number', 
        'qr_code_path', 
        'status'
    ];
    // Tambahkan di dalam class Table
    public function activeOrder()
    {
        // Mengambil 1 order terakhir yang statusnya belum selesai/batal
        return $this->hasOne(Order::class)->whereNotIn('status', ['completed', 'cancelled'])->latest();
    }
}