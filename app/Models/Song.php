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
use Illuminate\Support\Carbon;

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
}
