<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
 protected $fillable = [
        'name',
        'google_maps_embed_url',
        'address',
        'contact_phone',
    ];

     protected $casts = [
        'contact_phone' => 'array',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
