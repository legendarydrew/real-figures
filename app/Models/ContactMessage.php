<?php

namespace App\Models;

use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getDates(): array
    {
        return ['created_at', 'updated_at', 'read_at'];
    }

    public function getWasReadAttribute(): bool
    {
        return !is_null($this->read_at);
    }
}
