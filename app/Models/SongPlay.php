<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded(['id'])]
class SongPlay extends Model
{
    public function getDates(): array
    {
        return ['played_on', 'created_at', 'updated_at'];
    }

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
