<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActMetaTrait extends Model
{
    /** @use HasFactory<\Database\Factories\ActMetaTraitFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function act(): BelongsTo
    {
        return $this->belongsTo(Act::class);
    }
}
