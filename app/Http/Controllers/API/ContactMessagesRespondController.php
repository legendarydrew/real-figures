<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageResponseRequest;
use App\Mail\ContactMessageResponse;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;

class ContactMessagesRespondController extends Controller
{
    public function update(ContactMessageResponseRequest $request, int $message_id): void
    {
        $message = ContactMessage::findOrFail($message_id);
        $data = $request->validated();

        Mail::to($message->email)->send(new ContactMessageResponse($message, $data['response']));
    }
}
