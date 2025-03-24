<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
