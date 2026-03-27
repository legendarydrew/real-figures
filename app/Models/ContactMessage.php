<?php

namespace App\Models;

use Database\Factories\ContactMessageFactory;
use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Guarded('id', 'created_at', 'updated_at')]
class ContactMessage extends Model
{
    /** @use HasFactory<ContactMessageFactory> */
    use HasFactory;

    public function getDates(): array
    {
        return ['created_at', 'updated_at', 'read_at'];
    }

    public function getWasReadAttribute(): bool
    {
        return ! is_null($this->read_at);
    }
}
