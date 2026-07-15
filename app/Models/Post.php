<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
        'status',
        'votes',
        'github_url',
        'demo_url',
        'image_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function votesRelation(): HasMany
    {
        return $this->hasMany(PostVote::class);
    }

    public function bookmarksRelation(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function userVoteType($userId)
    {
        if (!$userId) return null;
        $vote = $this->votesRelation()->where('user_id', $userId)->first();
        return $vote ? $vote->type : null;
    }

    public function isBookmarkedBy($userId)
    {
        if (!$userId) return false;
        return $this->bookmarksRelation()->where('user_id', $userId)->exists();
    }
}