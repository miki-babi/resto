<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PastryItemOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pastry_item_order_id',
        'pastry_item_id',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(PastryItemOrder::class, 'pastry_item_order_id');
    }

    public function item()
    {
        return $this->belongsTo(PastryItem::class, 'pastry_item_id');
    }
}