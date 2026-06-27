<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    /**
     * Kolom yang boleh diisi (mass assignable).
     * Sesuaikan dengan nama kolom yang ada di database kamu.
     */
    protected $fillable = [
        'title',
        'content',
        'user_id',
        'category_id',
        'status'
    ];

    /**
     * Relasi: Satu post dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu post dimiliki oleh satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}