<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class NewsPost extends Model
{
    /** @use HasFactory<\Database\Factories\NewsPostFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function getExcerptAttribute(): string
    {
        return Str::words($this->content, 20);
    }

    public function references(): HasMany
    {
        return $this->hasMany(NewsPostReference::class);
    }
}
