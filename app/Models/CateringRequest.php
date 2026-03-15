<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CateringRequest extends Model
{
    protected $fillable = [
        'catering_package_id',
        'name',
        'contact',
        'note',
        'status',
    ];

     protected static function booted()
    {
        static::created(function ($request) {

            Customer::firstOrCreate(
                ['phone' => $request->contact],
                [
                    'name' => $request->name,
                    'notes' => $request->note
                ]
            );

        });
    }

    public function package()
    {
        return $this->belongsTo(CateringPackage::class, 'catering_package_id');
    }
}