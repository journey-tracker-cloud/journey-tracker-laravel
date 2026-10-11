<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Concerns;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use JourneyTracker\JourneyTrackerLaravel\Enums\IngestType;
use JourneyTracker\JourneyTrackerLaravel\Events\JourneyTrackerBlocked;
use JourneyTracker\JourneyTrackerLaravel\Events\JourneyTrackerFailed;
use Throwable;

trait SendsToJourneyTracker
{
    /** @param array<string, mixed> $payload */
    protected function send(IngestType $type, string $path, array $payload): ?Response
    {
        try {
            return Http::journeyTracker()->throw()->post($path, $payload);
        } catch (RequestException $exception) {
            $status = $exception->response->status();

            event($status === 402
                ? new JourneyTrackerBlocked($type)
                : new JourneyTrackerFailed($type, $status, $exception));

            return null;
        } catch (Throwable $exception) {
            event(new JourneyTrackerFailed($type, null, $exception));

            return null;
        }
    }
}
