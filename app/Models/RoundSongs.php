<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class RoundSongs extends Model
{

    protected $guarded = ['id'];

    public function stage(): HasOneThrough
    {
        return $this->hasOneThrough(Stage::class, Round::class, 'id', 'id', 'id', 'stage_id');
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
