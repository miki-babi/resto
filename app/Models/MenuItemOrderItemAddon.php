<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemOrderItemAddon extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_order_item_id',
        'menu_item_addon_id',
        'name',
        'price',
    ];

    public function item()
    {
        return $this->belongsTo(MenuItemOrderItem::class, 'menu_item_order_item_id');
    }

    public function addon()
    {
        return $this->belongsTo(MenuItemAddon::class, 'menu_item_addon_id');
    }
}

