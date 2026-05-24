<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menu extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'price', 'image', 'is_available'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($menu) { $menu->slug = Str::slug($menu->name); });
        static::updating(function ($menu) { $menu->slug = Str::slug($menu->name); });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
