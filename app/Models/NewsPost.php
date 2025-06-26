<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsPost extends Model
{
    /** @use HasFactory<\Database\Factories\NewsPostFactory> */
    use HasFactory;

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function getExcerptAttribute(): string
    {
        return Str::words($this->content, 20);
    }
}
