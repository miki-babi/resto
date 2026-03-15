<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackLink extends Model
{
    protected $fillable = ['name', 'address', 'google_review_link'];

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
    public function getFeedbackUrlAttribute()
    {
        return url("/feedback/{$this->id}");
    }
}
