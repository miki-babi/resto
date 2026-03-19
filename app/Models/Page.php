<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;



class Page extends Model  implements HasMedia
{
    //
    use InteractsWithMedia;
    protected $fillable = [
        'title',
        'slug',
        'hero_headline',
        'hero_subtitle',
        'primary_cta_text',
        'primary_cta_url',
        'secondary_cta_text',
        'secondary_cta_url',
        'location_id',
        'menu_category_id',
        'gallery_id'
    ];
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);    
    }
     public function registerMediaCollections(): void
    {
        $this->addMediaCollection('hero_image')->singleFile();
        $this->addMediaCollection('hero_video')->singleFile();
    }
}
