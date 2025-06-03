<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Act extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function profile(): HasOne
    {
        return $this->hasOne(ActProfile::class);
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    public function picture(): HasOne
    {
        return $this->hasOne(ActPicture::class);
    }
}
