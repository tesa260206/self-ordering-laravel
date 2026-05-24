<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 
        'table_id', 
        'customer_name', 
        'total_amount', 
        'status', 
        'payment_status'
    ];

    // 1. Relasi ke Meja
    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    // 2. Relasi ke Detail Pesanan (INI YANG TADI KURANG)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 3. Relasi ke Pembayaran (Untuk fitur Kasir)
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}