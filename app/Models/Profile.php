<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Profile extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'address',
        'phone_numbers',
        'social_media_links',
    ];

    protected $casts = [
        'address' => 'array',
        'phone_numbers' => 'array',
        'social_media_links' => 'array',
    ];
    //
}
