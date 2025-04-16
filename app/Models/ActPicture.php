<?php

namespace App\Models;

use Database\Factories\ActPictureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActPicture extends Model
{
    /** @use HasFactory<ActPictureFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function act(): BelongsTo
    {
        return $this->belongsTo(Act::class);
    }
}
