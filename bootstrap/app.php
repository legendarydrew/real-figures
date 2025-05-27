<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Jobs\EndOfRound;
use App\Jobs\PurgeUnconfirmedSubscribers;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
                  ->withRouting(
                      web: __DIR__ . '/../routes/web.php',
                      commands: __DIR__ . '/../routes/console.php',
                      health: '/up',
                  )
                  ->withMiddleware(function (Middleware $middleware)
                  {
                      $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

                      $middleware->web(append: [
                          HandleAppearance::class,
                          HandleInertiaRequests::class,
                          AddLinkHeadersForPreloadedAssets::class,
                      ]);
                  })
                  ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule)
                  {
                      // Define scheduled tasks here. (Don't forget to define a cron task.)
                      $schedule->call(EndOfRound::class)->hourly();
                      $schedule->call(PurgeUnconfirmedSubscribers::class)->hourly();
                  })
                  ->withExceptions(function (Exceptions $exceptions)
                  {
                      // Handle exceptions here.
                      // https://inertiajs.com/error-handling
                      $exceptions->respond(function (Response $response, Throwable $exception, Request $request)
                      {
                          if (!app()->environment(['local', 'testing']) && in_array($response->getStatusCode(), [500, 503, 404, 403]))
                          {
                              return Inertia::render('error', ['status' => $response->getStatusCode()])
                                            ->toResponse($request)
                                            ->setStatusCode($response->getStatusCode());
                          }
                          elseif ($response->getStatusCode() === 419)
                          {
                              return back()->with([
                                  'message' => 'The page has expired, please try again.',
                              ]);
                          }

                          logger()->error($exception);

                          return $response;
                      });

                      // Report exceptions through Sentry.
                      Integration::handles($exceptions);
                  })->create();
