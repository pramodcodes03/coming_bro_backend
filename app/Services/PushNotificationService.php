<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Best-effort Firebase Cloud Messaging sender. Used to deliver high-priority
 * alerts (with sound) to a customer or driver device for the Return Ride flow
 * — e.g. notifying a customer the moment a driver submits a fare offer.
 *
 * Real-time delivery already happens over WebSockets; this adds an OS-level
 * push (and alert sound) when the app is backgrounded. It is intentionally
 * defensive: if no FCM server key is configured it logs and no-ops so the
 * request flow and the test suite never fail because of a missing credential.
 */
class PushNotificationService
{
    private const LEGACY_ENDPOINT = 'https://fcm.googleapis.com/fcm/send';

    /**
     * Send a notification to a single device token.
     *
     * @param  array<string,mixed>  $data  Extra data payload (e.g. ['type' => 'return_ride_offer']).
     */
    public function sendToToken(?string $token, string $title, string $body, array $data = []): void
    {
        if (empty($token)) {
            return;
        }

        $serverKey = config('services.fcm.server_key');
        if (empty($serverKey)) {
            Log::info('[FCM] Skipped push — no server key configured.', [
                'title' => $title,
                'type'  => $data['type'] ?? null,
            ]);
            return;
        }

        try {
            Http::withHeaders([
                'Authorization' => 'key=' . $serverKey,
                'Content-Type'  => 'application/json',
            ])->timeout(5)->post(self::LEGACY_ENDPOINT, [
                'to'       => $token,
                'priority' => 'high',
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                    'sound' => 'default',
                ],
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            // Never let a push failure break the originating request.
            Log::warning('[FCM] Push send failed.', [
                'error' => $e->getMessage(),
                'type'  => $data['type'] ?? null,
            ]);
        }
    }
}
