<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingContent extends Model
{
    protected $fillable = [
        'section',
        'title',
        'content',
        'image',
        'order',
        'status'
    ];

    protected $casts = [
        'content' => 'array',
        'status' => 'boolean'
    ];
} 