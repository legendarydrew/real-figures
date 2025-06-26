<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsPost extends Model
{
    /** @use HasFactory<\Database\Factories\NewsPostFactory> */
    use HasFactory;

    public function getExcerptAttribute(): string
    {
        return Str::words($this->content, 20);
    }
}
