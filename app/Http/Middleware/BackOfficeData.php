<?php

namespace App\Http\Middleware;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * BackOfficeData
 * This middleware will make shared data available to the back office pages:
 * things we probably shouldn't include for the front-facing site.
 */
class BackOfficeData extends Middleware
{
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'admin' => [
                'unread_messages' => fn () => ContactMessage::whereNull('read_at')->count(),
            ],
        ];
    }
}
