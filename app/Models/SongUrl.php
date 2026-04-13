<?php

namespace App\Models;

use Database\Factories\SongUrlFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded(['id', 'created_at', 'updated_at'])]
class SongUrl extends Model
{
    /** @use HasFactory<SongUrlFactory> */
    use HasFactory;

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    /**
     * Returns the video ID from the URL, if possible.
     */
    public function getVideoIdAttribute(): ?string
    {
        $urls = [
            'tiny' => '/^https:\/\/youtu\.be\/([\w-]+)\S*/',
            'shorts' => '/^https?:\/\/(?:www.)?youtube\.com\/shorts\/([\w-]+)\S*/',
            'normal' => '/^https?:\/\/(?:www.)?youtube\.com\/watch\?v=([\w-]+)\S*/',
        ];
        // NOTE: the (?:www.)? part of the regex denotes a non-capturing group.

        $matches = [];
        foreach ($urls as $regex) {
            $outcome = preg_match($regex, $this->url, $matches);
            if ($outcome === 1) {
                return $matches[1];
            }
        }

        return null;
    }
}
