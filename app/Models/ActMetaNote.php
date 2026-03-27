<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Guarded('id', 'created_at', 'updated_at')]
class ActMetaNote extends Model
{
    /** @use HasFactory<\Database\Factories\ActMetaNoteFactory> */
    use HasFactory;

    public function act(): BelongsTo
    {
        return $this->belongsTo(Act::class);
    }
}
