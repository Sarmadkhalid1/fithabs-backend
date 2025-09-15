<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
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
        'width',
        'height',
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
        return Storage::disk('images')->url($this->path);
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

    public function getDimensions()
    {
        if ($this->width && $this->height) {
            return $this->width . 'x' . $this->height;
        }
        return null;
    }
}