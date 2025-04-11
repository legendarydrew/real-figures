<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int                                                                  $id
 * @property string                                                               $name
 * @property string                                                               $slug
 * @property \Illuminate\Support\Carbon|null                                      $created_at
 * @property \Illuminate\Support\Carbon|null                                      $updated_at
 * @property-read \App\Models\ActProfile|null                                     $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null                                                        $songs_count
 * @method static \Database\Factories\ActFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Act newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Act newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Act query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Act whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Act whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Act whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Act whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Act whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Act extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function profile(): HasOne
    {
        return $this->hasOne(ActProfile::class);
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }
}
