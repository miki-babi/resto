<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Review extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'reviewer_name',
        'content',
        'stars',
        'is_featured',
        'sort_order',
    ];

    // Media collection for reviewer avatar
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useDisk('public')
            ->singleFile()
            ->withResponsiveImages();
    }

    // Scope featured reviews
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}