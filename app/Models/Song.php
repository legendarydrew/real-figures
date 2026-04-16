<?php

namespace App\Models;

use App\Enums\RoundWinState;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

#[Guarded(['id', 'created_at', 'updated_at'])]
class Song extends Model
{
    use HasFactory;

    public function accolades(): HasMany
    {
        return $this->hasMany(StageWinner::class);
    }

    public function act(): BelongsTo
    {
        return $this->belongsTo(Act::class);
    }

    public function language(): HasOne
    {
        return $this->hasOne(Language::class, 'id', 'language_id');
    }

    public function plays(): HasMany
    {
        return $this->hasMany(SongPlay::class);
    }

    public function rounds(): HasManyThrough
    {
        return $this->hasManyThrough(Round::class, RoundSongs::class, 'song_id', 'id', 'id', 'round_id');
    }

    public function wins(): HasMany
    {
        return $this->hasMany(StageWinner::class);
    }

    /**
     * Returns the total number of times the Song was recorded as having been played.
     */
    public function getPlayCountAttribute(): int
    {
        return $this->plays()->sum('play_count');
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(RoundOutcome::class);
    }

    /**
     * Returns the most recent URL associated with this Song.
     *
     * @return SongUrl|null
     */
    public function latestVersion(): SongUrl|null
    {
        return $this->urls()->latest()->first();
    }

    public function urls(): HasMany
    {
        return $this->hasMany(SongUrl::class);
    }

    public function goldenBuzzers(): HasMany
    {
        return $this->hasMany(GoldenBuzzer::class);
    }

    /**
     * Set whether the song can receive Golden Buzzers.
     */
    public function setGoldenBuzzerStatus(bool $state): void
    {
        if ($state)
        {
            DB::table('golden_buzzer_songs')
              ->updateOrInsert(['song_id' => $this->id]);
        }
        else
        {
            DB::table('golden_buzzer_songs')
              ->where('song_id', $this->id)
              ->delete();
        }
    }

    /**
     * Returns TRUE if the Song received a Golden Buzzer in the specified Round.
     */
    public function hasGoldenBuzzer(Round $round): bool
    {
        return $this->goldenBuzzers->some('round_id', $round->id);
    }

    /**
     * Determines whether this Song was a winner or a runner-up in the specified Round.
     */
    public function roundWinStatus(Round $round): RoundWinState
    {
        $win = $this->wins()->where('round_id', $round->id)->first();

        if ($win)
        {
            return $win->is_winner ? RoundWinState::WINNER : RoundWinState::RUNNER_UP;
        }

        return RoundWinState::NONE;
    }
}
