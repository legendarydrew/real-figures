<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Genre extends Model
{
    /** @use HasFactory<\Database\Factories\GenreFactory> */
    use HasFactory;

    public function acts(): HasManyThrough
    {
        return $this->hasManyThrough(Act::class, ActMetaGenre::class, 'genre_id', 'id', 'id', 'act_id');
    }
}
