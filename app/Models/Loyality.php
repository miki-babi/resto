<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Loyality extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'points_required',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'points_required' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function redemptions()
    {
        return $this->hasMany(LoyalityRedemption::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reward_image')
            ->useDisk('public')
            ->singleFile()
            ->withResponsiveImages();
    }
}
