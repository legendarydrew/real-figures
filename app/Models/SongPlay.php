<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SongPlay extends Model
{
    protected $guarded = ['id'];

    public function getDates(): array
    {
        return ['played_on', 'created_at', 'updated_at'];
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
