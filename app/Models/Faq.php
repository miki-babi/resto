<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Faq extends Model
{
    //
    protected $fillable = [
        'question',
        'answer',
        'slug',
        'meta_title',
        'meta_description',
        'is_active',
        'sort_order',
    ];

    /*
    |--------------------------------------------------------------------------
    | Auto slug generation
    |--------------------------------------------------------------------------
    */
    protected static function booted()
    {
        static::creating(function ($faq) {
            if (!$faq->slug) {
                $faq->slug = Str::slug($faq->question);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SEO Helpers (fallbacks)
    |--------------------------------------------------------------------------
    */

    // Title for SEO
    public function getSeoTitleAttribute()
    {
        return $this->meta_title ?: $this->question;
    }

    // Description for SEO
    public function getSeoDescriptionAttribute()
    {
        return $this->meta_description
            ?: Str::limit(strip_tags($this->answer), 160);
    }

    /*
    |--------------------------------------------------------------------------
    | Scope (clean querying)
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Route binding (optional clean URLs)
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
