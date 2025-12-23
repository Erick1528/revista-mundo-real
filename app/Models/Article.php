<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $fillable = [
        'title',
        'subtitle',
        'attribution',
        'summary',
        'slug',
        'image_path',
        'visibility',
        'status',
        'published_at',
        'section',
        'tags',
        'related_articles',
        'content',
        'view_count',
        'reading_time',
        'meta_description',
        'user_id'
    ];

    protected $casts = [
        'tags' => 'array',
        'related_articles' => 'array',
        'content' => 'array',
        'published_at' => 'datetime',
        'view_count' => 'integer',
        'reading_time' => 'integer',
    ];
}
