<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class RoundVote extends Model
{
    protected $guarded = ['id'];

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function stage(): HasOneThrough
    {
        return $this->hasOneThrough(Stage::class, Round::class, 'id', 'id', 'id', 'stage_id');
    }

    public function first_choice(): BelongsTo
    {
        return $this->belongsTo(Song::class, 'first_choice_id');
    }

    public function second_choice(): BelongsTo
    {
        return $this->belongsTo(Song::class, 'second_choice_id');
    }

    public function third_choice(): BelongsTo
    {
        return $this->belongsTo(Song::class, 'third_choice_id');
    }

}
