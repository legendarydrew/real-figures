<?php

namespace App\Transformers;

use App\Models\ContactMessage;
use League\Fractal\TransformerAbstract;

class ContactMessageTransformer extends TransformerAbstract
{

    public function transform(ContactMessage $message): array
    {
        return [
            'id'      => (int)$message->id,
            'name'    => $message->name,
            'email'   => $message->email,
            'ip'      => $message->ip_address,
            'body'    => $message->body,
            'sent_at' => $message->created_at->format(config('contest.date_format')),
            'is_spam' => (bool)$message->is_spam
        ];
    }
}
