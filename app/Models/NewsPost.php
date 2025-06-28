<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class NewsPost extends Model
{
    /** @use HasFactory<\Database\Factories\NewsPostFactory> */
    use HasFactory;
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
                          ->generateSlugsFrom('title')
                          ->saveSlugsTo('slug');
    }

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getDates(): array
    {
        return ['created_at', 'updated_at', 'published_at'];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function getExcerptAttribute(): string
    {
        $text_content = strip_tags(Str::markdown($this->content));
        return Str::words($text_content, 20);
    }

    public function getUrlAttribute()
    {
        return route('news.show', ['slug' => $this->slug]);
    }

    public function references(): HasMany
    {
        return $this->hasMany(NewsPostReference::class);
    }
}
