<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Str;

/**
 * AnalyticsEvents
 * copied from https://github.com/redafillali/google-analytics-events
 *
 * @package App\Services
 */
class AnalyticsEvents
{
    protected string $measurementId;
    protected string $apiSecret;

    public function __construct()
    {
        $this->measurementId = config('services.analytics.measurement_id');
        $this->apiSecret     = config('services.analytics.api_secret');
    }

    public function send(string $eventName, array $params = [], ?string $clientId = null): void
    {
        $clientId = $clientId ?: $this->getClientId();

        if (!$clientId)
        {
            return;
        }

        $url = "https://www.google-analytics.com/mp/collect?measurement_id={$this->measurementId}&api_secret={$this->apiSecret}";

        Http::post($url, [
            'client_id' => $clientId,
            'events'    => [
                [
                    'name'   => $eventName,
                    'params' => $params,
                ],
            ],
        ]);
    }

    protected function getClientId(): string
    {
        $cookie = request()->cookie('_ga');
        if ($cookie && preg_match('/GA\d\.\d\.(\d+\.\d+)/', $cookie, $matches))
        {
            return $matches[1];
        }

        return (string)Str::uuid();
    }
}
