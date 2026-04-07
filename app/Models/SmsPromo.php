<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsPromo extends Model
{
    //
    protected $fillable = [
        'content',
        'status',
    ];
}
