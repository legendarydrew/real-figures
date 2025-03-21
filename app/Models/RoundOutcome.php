<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoundOutcome extends Model
{
    /** @use HasFactory<\Database\Factories\RoundOutcomeFactory> */
    use HasFactory;

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
