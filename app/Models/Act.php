<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Act extends Model
{
    use HasFactory;

    public function profile(): HasOne
    {
        return $this->hasOne(ActProfile::class);
    }
}
