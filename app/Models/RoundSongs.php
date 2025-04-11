<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 *
 *
 * @property int                             $id
 * @property int                             $round_id
 * @property int                             $song_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Round          $round
 * @property-read \App\Models\Song           $song
 * @property-read \App\Models\Stage|null     $stage
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundSongs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundSongs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundSongs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundSongs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundSongs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundSongs whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundSongs whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundSongs whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RoundSongs extends Model
{

    protected $guarded = ['id'];

    public function stage(): HasOneThrough
    {
        return $this->hasOneThrough(Stage::class, Round::class, 'id', 'id', 'id', 'stage_id');
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
