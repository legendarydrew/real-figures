<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int                                                                          $id
 * @property int                                                                          $act_id
 * @property string                                                                       $title
 * @property int                                                                          $play_count
 * @property \Illuminate\Support\Carbon|null                                              $created_at
 * @property \Illuminate\Support\Carbon|null                                              $updated_at
 * @property-read \App\Models\Act                                                         $act
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoundOutcome> $outcomes
 * @property-read int|null                                                                $outcomes_count
 * @method static \Database\Factories\SongFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song wherePlayCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Song extends Model
{
    use HasFactory;

    public function act(): BelongsTo
    {
        return $this->belongsTo(Act::class);
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(RoundOutcome::class);
    }
}
