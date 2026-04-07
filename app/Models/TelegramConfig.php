<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramConfig extends Model
{
    protected $fillable = [
        'bot_token',
        'miniapp_url',
        'start_message',
        'help_message',
    ];
}
