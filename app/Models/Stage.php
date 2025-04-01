<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
