<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class MenuItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'menu_category_id',
        'title',
        'slug',
        'description',
        'price',
        'is_available',
        'is_featured',
        'sort_order'
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function variants()
    {
        return $this->hasMany(MenuItemVariant::class);
    }

    public function addons()
    {
        return $this->hasMany(MenuItemAddon::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('menu_images')
            ->useDisk('public');
    }
}
