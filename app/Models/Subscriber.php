<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriberFactory> */
    use HasFactory;

    protected $fillable = ['email', 'confirmed'];

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('confirmed', '=', true);
    }
}
