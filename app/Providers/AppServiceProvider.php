<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
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
            'analytics' => config('services.analytics'),
            'donation'  => config('contest.donation'),
            'locale'    => config('app.locale')
        ]);

        \View::composer('front.links', function(View $view) {
            // Determine the current route.
            $view->with('current', Route::currentRouteName());
        });
    }
}
