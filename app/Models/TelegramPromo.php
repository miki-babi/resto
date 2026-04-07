<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TelegramPromo extends Model implements HasMedia
{
    use InteractsWithMedia;

    //
    protected $fillable = [
        'caption',
        'cta_label',
        'cta_link',
        'status',
    ];
}
