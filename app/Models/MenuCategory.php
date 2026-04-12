<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function scopeActiveForDelivery($query)
    {
        return $query->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
