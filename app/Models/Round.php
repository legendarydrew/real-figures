<?php

namespace App\Models;

use Database\Factories\RoundFactory;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeStarted(Builder $builder): Builder
    {
        return $builder->where('starts_at', '<=', now());
    }

    public function scopeEnded(Builder $builder): Builder
    {
        return $builder->where('ends_at', '>', now());
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('starts_at', '<=', now())
                       ->where('ends_at', '>', now());
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
     * Returns TRUE if the Round is active/underway.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        $now = now();
        return $this->starts_at < $now && $this->ends_at > $now;
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

    /**
     * Returns TRUE if this Round requires a "manual vote".
     * This happens if the Round has RoundOutcomes, but all the Songs have zero points.
     *
     * @return bool
     */
    public function requiresManualVote(): bool
    {
        return $this->hasEnded() && $this->songs()->count() > 0 && $this->outcomes->every('score', '=', 0);
    }

    public function getFullTitleAttribute(): string
    {
        $stage_round_count = $this->stage->rounds()->count();
        $key               = $stage_round_count === 1 ? 'contest.round.title.only_round' : 'contest.round.title.many_rounds';
        return trans($key, [
            'stage_title' => $this->stage->title,
            'round_title' => $this->title
        ]);
    }
}
