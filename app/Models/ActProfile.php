<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int                             $id
 * @property int                             $act_id
 * @property string                          $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Act            $act
 * @method static \Database\Factories\ActProfileFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActProfile whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActProfile whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActProfile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ActProfile extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function act(): BelongsTo
    {
        return $this->belongsTo(Act::class);
    }
}
