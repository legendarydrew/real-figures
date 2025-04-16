<?php

namespace App\Models;

use Database\Factories\SongUrlFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SongUrl extends Model
{
    /** @use HasFactory<SongUrlFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
