<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducationContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by_admin',
        'title',
        'description',
        'image_url',
        'content',
        'content_type',
        'video_url',
        'category',
        'tags',
        'read_time_minutes',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    // Relationships
    public function admin()
    {
        return $this->belongsTo(AdminUser::class, 'created_by_admin');
    }
}