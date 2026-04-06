<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

#[Guarded(['id', 'created_at', 'updated_at'])]
class NewsPost extends Model implements Feedable
{
    use HasFactory;

    /** @use HasFactory<\Database\Factories\NewsPostFactory> */
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

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

    public function getUrlAttribute(): string
    {
        return route('news.show', ['slug' => $this->slug]);
    }

    /**
     * Returns the first published NewsPost before this one.
     */
    public function previousPost(): ?NewsPost
    {
        return NewsPost::published()->where('id', '<', $this->id)->orderByDesc('id')->first();
    }

    /**
     * Returns the first published NewsPost after this one.
     */
    public function nextPost(): ?NewsPost
    {
        return NewsPost::published()->where('id', '>', $this->id)->orderBy('id')->first();
    }

    /**
     * Returns a list of the most recent published NewsPosts, not including this one or the
     * previous or next posts.
     *
     * @return Collection<NewsPost>|null
     */
    public function otherRecentPosts(): ?Collection
    {
        return NewsPost::published()
            ->wherenotIn('id', [$this->id, $this->previousPost()?->id, $this->nextPost()?->id])
            ->orderByDesc('id')
            ->take(4)
            ->get();
    }

    /**
     * Returns a list of Acts mentioned (by name) in this News post.
     */
    public function actsMentioned(): Collection
    {
        return Act::get()->filter(fn (Act $act) => str_contains(strtolower($this->content), strtolower($act->name)));
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->excerpt)
            ->updated($this->updated_at)
            ->link($this->url)
            ->authorName(config('contest.feed.author'))
            ->authorEmail(config('contest.feed.email'));
    }

    public static function getFeedItems()
    {
        return NewsPost::published()->get();
    }
}
