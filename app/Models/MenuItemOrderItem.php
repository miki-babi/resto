<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_order_id',
        'menu_item_id',
        'menu_item_variant_id',
        'title',
        'variant_name',
        'unit_price',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(MenuItemOrder::class, 'menu_item_order_id');
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function variant()
    {
        return $this->belongsTo(MenuItemVariant::class, 'menu_item_variant_id');
    }

    public function addons()
    {
        return $this->hasMany(MenuItemOrderItemAddon::class);
    }
}

