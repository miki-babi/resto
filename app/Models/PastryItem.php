<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PastryItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['name', 'description', 'price', 'is_active'];

    public function pastryPackages()
    {
        return $this->belongsToMany(PastryPackage::class, 'pastry_package_items')
                    ->withPivot(['amount', 'price', 'show_price'])
                    ->withTimestamps();
    }

    public function packages()
    {
        return $this->pastryPackages();
    }
}
