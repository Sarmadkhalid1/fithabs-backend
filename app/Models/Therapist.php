<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Therapist extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'profile_image',
        'specializations',
        'certifications',
        'phone',
        'clinic_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'specializations' => 'array',
        'certifications' => 'array',
        'password' => 'hashed',
    ];

    // Relationships
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}