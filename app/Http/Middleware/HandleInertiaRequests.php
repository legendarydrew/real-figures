<?php

namespace App\Http\Middleware;

use App\Facades\ContestFacade;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name'     => config('app.name'),
            'auth'     => [
                'user' => $request->user(),
            ],
            'ziggy'    => fn(): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'showActs' => ContestFacade::shouldShowActs(),
            'showNews' => ContestFacade::shouldShowNews(),
            'sidebarOpen' => $request->cookie('sidebar_state') === 'true',
            'flash'    => [
                'message' => fn() => $request->session()->get('message'),
                'track'   => fn() => $request->session()->get('track')
            ],
        ];
    }
}
