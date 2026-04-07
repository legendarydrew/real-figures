<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PressReleaseException extends Exception
{
    /**
     * Report the exception.
     * Optional: Log the incident or send a notification (e.g., to Slack).
     */
    public function report(): bool
    {
        Log::warning('PressReleaseException: ' . $this->getMessage());
        return false;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render(): Response
    {
        // 1. Define a human-readable message based on the exception message
        $message = 'PressReleaseException: ' . $this->getMessage();

        // 2. Return a custom JSON response
        return response()->json([
            'status'  => 'error',
            'code'    => Response::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $message,
            'reason'  => 'Server Error',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
