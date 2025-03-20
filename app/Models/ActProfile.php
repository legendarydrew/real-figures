<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActProfile extends Model
{

    use HasFactory;

    public function act(): BelongsTo
    {
        return $this->belongsTo(Act::class);
    }
}
