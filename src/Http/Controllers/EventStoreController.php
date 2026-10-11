<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Http\Controllers;

use Illuminate\Http\Response;
use JourneyTracker\JourneyTrackerLaravel\Http\Requests\EventStoreRequest;
use JourneyTracker\JourneyTrackerLaravel\Jobs\LogPageEventJob;
use JourneyTracker\JourneyTrackerLaravel\JourneyTracker;

class EventStoreController
{
    public function __invoke(EventStoreRequest $request, JourneyTracker $journeyTracker): Response
    {
        LogPageEventJob::dispatch($request->toData())
            ->onQueue($journeyTracker->queue());

        return response()->noContent();
    }
}
