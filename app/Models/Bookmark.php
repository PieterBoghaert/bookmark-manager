<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bookmark extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'url',
        'favicon',
        'view_count',
        'last_visited_at',
        'is_pinned',
        'is_archived',
    ];

    protected $casts = [
        'last_visited_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
