<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemAddon extends Model
{
    protected $fillable = [
        'menu_item_id',
        'name',
        'price',
        'sort_order',
        'is_active'
    ];

    public function item()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
