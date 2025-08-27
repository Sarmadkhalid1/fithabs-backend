<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CacheLock extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'key',
        'owner',
        'expiration',
    ];

    protected $primaryKey = 'key';

    public $incrementing = false;
}