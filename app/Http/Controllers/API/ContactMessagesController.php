<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Http\Requests\ContactMessageResponseRequest;
use App\Mail\ContactMessageResponse;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class ContactMessagesController extends Controller
{

    public function store(ContactMessageRequest $request): Response
    {
        $data = $request->validated();

        ContactMessage::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'body'    => $data['body'],
            'is_spam' => !$this->validateResponse($data['token']),
            'ip'      => request()->ip(),
        ]);

        return Inertia::render('front/contact', [
            'success' => true,
        ]);
    }

    /**
     * Validates the specified token with Cloudflare Turnstile.
     * Returns TRUE if the token was successfully validated.
     * NOTE: tokens can only be used/validated once.
     *
     * @param string $token
     * @return bool
     */
    protected function validateResponse(string $token): bool
    {
        // https://developers.cloudflare.com/turnstile/get-started/server-side-validation/
        $payload  = [
            'secret'   => $token,
            'response' => request('response'),
            'remoteip' => request()->ip()
        ];
        $response = Http::post("https://challenges.cloudflare.com/turnstile/v0/siteverify", $payload);
        if ($response->successful())
        {
            return (bool)$response->json('success');
        }
        return false;
    }

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
