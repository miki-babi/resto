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
        'title',
        'selected_variant_id',
        'selected_variant_name',
        'selected_variant_price',
        'quantity',
        'price',
        'addons_unit_price',
        'selected_addons',
        'line_total_price',
    ];

    protected $casts = [
        'selected_addons' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(MenuItemOrder::class, 'menu_item_order_id');
    }

    public function item()
    {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }
}
