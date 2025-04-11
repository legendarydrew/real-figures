<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int                                                                   $id
 * @property string                                                                $title
 * @property string                                                                $description
 * @property \Illuminate\Support\Carbon|null                                       $created_at
 * @property \Illuminate\Support\Carbon|null                                       $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Round> $rounds
 * @property-read int|null                                                         $rounds_count
 * @method static \Database\Factories\StageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Stage extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function rounds(): HasMany
    {
        return $this->hasMany(Round::class);
    }

    /**
     * Returns TRUE if at least one Round in this Stage has started.
     *
     * @return bool
     */
    public function hasStarted(): bool
    {
        return $this->rounds->some(fn(Round $round) => $round->hasStarted());
    }

    /**
     * Returns TRUE if all Rounds in this Stage have ended.
     *
     * @return bool
     */
    public function hasEnded(): bool
    {
        return $this->rounds->count() && $this->rounds->every(fn(Round $round) => $round->hasEnded());
    }
}
