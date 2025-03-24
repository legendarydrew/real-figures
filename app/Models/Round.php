<?php

namespace App\Models;

use Database\Factories\RoundFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Round extends Model
{
    /** @use HasFactory<RoundFactory> */
    use HasFactory;

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class, 'id');
    }

    public function outcomes(): HasMany
    {
        return $this->hasMany(RoundOutcome::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(RoundVote::class);
    }
}
