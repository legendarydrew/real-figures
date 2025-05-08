<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
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

    public function update(int $message_id): void
    {
        // This endpoint is used to mark the specified message as read.
        $message = ContactMessage::findOrFail($message_id);
        $message->update([
            'read_at' => now()
        ]);
    }

    public function destroy(): RedirectResponse
    {
        $message_ids = request('message_ids');
        ContactMessage::whereIn('id', $message_ids)->delete();

        return to_route('admin.contact');
    }
}
