<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_id',
        'name',
        'instructions',
        'video_url',
        'image_url',
        'duration_seconds',
        'repetitions',
        'sets',
        'rest_seconds',
        'order',
    ];

    // Relationships
    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}