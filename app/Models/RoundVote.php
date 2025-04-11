<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 *
 *
 * @property int                             $id
 * @property int                             $round_id
 * @property int                             $first_choice_id
 * @property int                             $second_choice_id
 * @property int                             $third_choice_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Song           $first_choice
 * @property-read \App\Models\Round          $round
 * @property-read \App\Models\Song           $second_choice
 * @property-read \App\Models\Stage|null     $stage
 * @property-read \App\Models\Song           $third_choice
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote whereFirstChoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote whereRoundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote whereSecondChoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote whereThirdChoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoundVote whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
