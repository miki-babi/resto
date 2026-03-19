<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Str;

class Gallery extends Model implements HasMedia
{
    use InteractsWithMedia;


    //
    protected $fillable = [
        'title',
        'public_title',
        'slug',
        'description',
        'is_active',
    ];

    protected static function booted()
    {
        static::creating(function ($gallery) {
            if (!$gallery->slug) {
                $gallery->slug = Str::slug($gallery->title);
            }
        });
    }

    // Media collection
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useDisk('public') // or s3 if needed
            ->withResponsiveImages(); // good for SEO + performance
    }
    public function getDisplayTitleAttribute()
    {
        return $this->public_title ?: $this->title;
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
