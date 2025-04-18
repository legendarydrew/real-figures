<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActRequest;
use App\Http\Requests\ContactMessageResponseRequest;
use App\Mail\ContactMessageResponse;
use App\Models\Act;
use App\Models\ActPicture;
use App\Models\ActProfile;
use App\Models\ContactMessage;
use App\Transformers\ActTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ContactMessagesController extends Controller
{

    public function update(ContactMessageResponseRequest $request, int $message_id): void
    {
        $message = ContactMessage::findOrFail($message_id);
        $data    = $request->validated();

        Mail::to($message->email)->send(new ContactMessageResponse($message, $data['response']));
    }

    public function destroy(): RedirectResponse
    {
        $message_ids = request('message_ids');
        ContactMessage::whereIn('id', $message_ids)->delete();

        return to_route('admin.contact');
    }
}
