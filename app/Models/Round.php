<?php

namespace App\Models;

use App\Transformers\SongTransformer;
use Database\Factories\RoundFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Guarded(['id'])]
class Round extends Model
{
    /** @use HasFactory<RoundFactory> */
    use HasFactory;

    public function getDates(): array
    {
        return ['starts_at', 'ends_at', 'created_at', 'updated_at'];
    }

    public function goldenBuzzers(): HasMany
    {
        return $this->hasMany(GoldenBuzzer::class);
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
     */
    public function hasStarted(): bool
    {
        return $this->starts_at < now();
    }

    /**
     * Returns TRUE if the Round is active/underway.
     */
    public function isActive(): bool
    {
        $now = now();

        return $this->starts_at < $now && $this->ends_at > $now;
    }

    /**
     * Returns TRUE if the Round has ended.
     */
    public function hasEnded(): bool
    {
        return $this->ends_at < now();
    }

    /**
     * Returns TRUE if this Round requires a "manual vote".
     * This happens if the Round has RoundOutcomes, but all the Songs have zero points.
     */
    public function requiresManualVote(): bool
    {
        return $this->hasEnded() && $this->songs->isNotEmpty() &&
            ($this->votes->isEmpty() || $this->outcomes->every(fn (RoundOutcome $outcome) => $outcome->score === 0));
    }

    public function getFullTitleAttribute(): string
    {
        $stage_round_count = $this->stage->rounds()->count();
        $key = $stage_round_count === 1 ? 'contest.round.title.only_round' : 'contest.round.title.many_rounds';

        return trans($key, [
            'stage_title' => $this->stage->title,
            'round_title' => $this->title,
        ]);
    }

    public function randomVote(int $count = 1): void
    {
        $song_ids = $this->songs->map(fn(Song $song) => $song->id);

        for ($i = 0; $i < $count; $i++)
        {
            $choices = $song_ids->random(fake()->numberBetween(1, 3))
                                ->toArray();

            RoundVote::create([
                'round_id'         => $this->id,
                'first_choice_id'  => $choices[0],
                'second_choice_id' => $choices[1] ?? null,
                'third_choice_id'  => $choices[2] ?? null,
            ]);

        }
    }

    /**
     * Returns a "playlist" of Songs in this Round, for use in the Song player.
     * @return array
     */
    public function playlist(): array
    {
        return fractal($this->songs, new SongTransformer())->toArray();
    }
}
