<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Clinic extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'description',
        'logo',
        'phone',
        'address',
        'website',
        'services',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'services' => 'array',
        'password' => 'hashed',
    ];

    // Relationships
    public function therapists()
    {
        return $this->hasMany(Therapist::class);
    }
}