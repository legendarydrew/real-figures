<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoundVote extends Model
{

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
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
