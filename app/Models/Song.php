<?php

namespace App\Models;

use Database\Factories\SongFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 *
 *
 * @property int                                $id
 * @property int                                $act_id
 * @property string                             $title
 * @property int                                $play_count
 * @property Carbon|null                        $created_at
 * @property Carbon|null                        $updated_at
 * @property-read Act                           $act
 * @property-read Collection<int, RoundOutcome> $outcomes
 * @property-read int|null                      $outcomes_count
 * @method static SongFactory factory($count = null, $state = [])
 * @method static Builder<static>|Song newModelQuery()
 * @method static Builder<static>|Song newQuery()
 * @method static Builder<static>|Song query()
 * @method static Builder<static>|Song whereActId($value)
 * @method static Builder<static>|Song whereCreatedAt($value)
 * @method static Builder<static>|Song whereId($value)
 * @method static Builder<static>|Song wherePlayCount($value)
 * @method static Builder<static>|Song whereTitle($value)
 * @method static Builder<static>|Song whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Song extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function act(): BelongsTo
    {
        return $this->belongsTo(Act::class);
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(RoundOutcome::class);
    }

    public function url(): HasOne
    {
        // We should be able to switch to HasMany without rebuilding the database.
        return $this->hasOne(SongUrl::class);
    }

    public function goldenBuzzers(): HasMany
    {
        return $this->hasMany(GoldenBuzzer::class);
    }

    /**
     * Returns TRUE if this Song can receive a Golden Buzzer.
     *
     * @return bool
     */
    public function canReceiveGoldenBuzzer(): bool
    {
        return DB::table('golden_buzzer_songs')
                 ->where('song_id', $this->id)
                 ->count() > 0;
    }

    /**
     * Set whether the song can receive Golden Buzzers.
     *
     * @param bool $state
     * @return void
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
}
