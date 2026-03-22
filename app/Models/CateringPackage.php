<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CateringPackage extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'min_guests',
        'is_active',
        'price_per_person',
        'price_total',
        'badge_text',
        'badge_variant',
        'highlights',
    ];

    protected $casts = [
        'min_guests' => 'integer',
        'is_active' => 'boolean',
        'price_per_person' => 'decimal:2',
        'price_total' => 'decimal:2',
        'highlights' => 'array',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover_image')
            ->singleFile()
            ->useDisk('public');

        $this->addMediaCollection('gallery')
            ->useDisk('public');
    }

    public function requests()
{
    return $this->hasMany(CateringRequest::class);
}
}
