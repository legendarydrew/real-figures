<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StageWinner extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    public function round(): BelongsTo
    {
        return $this->belongsTo(Round::class);
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }

    public function getDescriptionAttribute(): string
    {
        $translate_key = $this->is_winner ? 'contest.song.accolade.winner' : 'contest.song.accolade.runner_up';
        return trans($translate_key, ['stage' => $this->stage->title, 'round' => $this->round->title]);
    }
}
