<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuggestedTopicRequest extends Model
{
    protected $fillable = [
        'suggested_topic_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function suggestedTopic(): BelongsTo
    {
        return $this->belongsTo(SuggestedTopic::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
