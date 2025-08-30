<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'filename',
        'path',
        'url',
        'mime_type',
        'file_size',
        'duration',
        'thumbnail_path',
        'thumbnail_url',
        'category',
        'tags',
        'is_active',
        'uploaded_by',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function uploader()
    {
        return $this->belongsTo(AdminUser::class, 'uploaded_by');
    }

    // Helper methods
    public function getFullUrl()
    {
        return Storage::disk('videos')->url($this->path);
    }

    public function getThumbnailFullUrl()
    {
        return $this->thumbnail_path ? Storage::disk('videos')->url($this->thumbnail_path) : null;
    }

    public function getFormattedFileSize()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFormattedDuration()
    {
        if (!$this->duration) return null;
        
        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
