<?php

namespace App\Models;

use Database\Factories\RoundFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 *
 *
 * @property int                                            $id
 * @property int                                            $stage_id
 * @property string                                         $title
 * @property \Illuminate\Support\Carbon                     $starts_at
 * @property \Illuminate\Support\Carbon                     $ends_at
 * @property \Illuminate\Support\Carbon|null                $created_at
 * @property \Illuminate\Support\Carbon|null                $updated_at
 * @property-read Collection<int, \App\Models\RoundOutcome> $outcomes
 * @property-read int|null                                  $outcomes_count
 * @property-read Collection<int, \App\Models\Song>         $songs
 * @property-read int|null                                  $songs_count
 * @property-read \App\Models\Stage                         $stage
 * @property-read Collection<int, \App\Models\RoundVote>    $votes
 * @property-read int|null                                  $votes_count
 * @method static \Database\Factories\RoundFactory factory($count = null, $state = [])
 * @method static Builder<static>|Round hasEnded()
 * @method static Builder<static>|Round hasStarted()
 * @method static Builder<static>|Round newModelQuery()
 * @method static Builder<static>|Round newQuery()
 * @method static Builder<static>|Round query()
 * @method static Builder<static>|Round whereCreatedAt($value)
 * @method static Builder<static>|Round whereEndsAt($value)
 * @method static Builder<static>|Round whereId($value)
 * @method static Builder<static>|Round whereStageId($value)
 * @method static Builder<static>|Round whereStartsAt($value)
 * @method static Builder<static>|Round whereTitle($value)
 * @method static Builder<static>|Round whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Round extends Model
{
    /** @use HasFactory<RoundFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function getDates(): array
    {
        return ['starts_at', 'ends_at', 'created_at', 'updated_at'];
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function songs(): HasManyThrough
    {
        return $this->hasManyThrough(Song::class, RoundSongs::class, 'round_id', 'id', 'id', 'song_id');
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(RoundOutcome::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(RoundVote::class);
    }

    public function scopeHasStarted(Builder $builder): void
    {
        $builder->where('starts_at', '<=', now());
    }

    public function scopeHasEnded(Builder $builder): void
    {
        $builder->where('ends_at', '>', now());
    }

    /**
     * Returns TRUE if the Round has started.
     *
     * @return bool
     */
    public function hasStarted(): bool
    {
        return $this->starts_at->isPast();
    }

    /**
     * Returns TRUE if the Round has ended.
     *
     * @return bool
     */
    public function hasEnded(): bool
    {
        return $this->ends_at->isPast();
    }
}
