<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Make certain configured values available to our app. (Thanks once again, ChatGPT.)
        Inertia::share([
            'adsense'          => config('services.adsense'),
            'analytics'        => config('services.analytics'),
            'donation'         => config('contest.donation'),
            'locale'           => config('app.locale'),
            'paypalClientId'   => config('services.paypal.client_id'),
            'turnstileSiteKey' => config('services.turnstile.site_key'),
        ]);
    }
}
