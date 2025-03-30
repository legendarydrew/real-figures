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
        return $this->starts_at < now();
    }

    /**
     * Returns TRUE if the Round has ended.
     *
     * @return bool
     */
    public function hasEnded(): bool
    {
        return $this->ends_at < now();
    }
}
