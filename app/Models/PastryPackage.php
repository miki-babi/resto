<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PastryPackage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name', 'description', 'is_active', 'is_customizable', 'show_item_price'];

    public function items()
    {
        return $this->belongsToMany(PastryItem::class, 'pastry_package_items')
                    ->withPivot(['amount', 'price', 'show_price'])
                    ->withTimestamps();
    }
}

