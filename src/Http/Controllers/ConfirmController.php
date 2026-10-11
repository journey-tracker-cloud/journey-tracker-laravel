<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Http\Controllers;

use Illuminate\Http\Response;
use JourneyTracker\JourneyTrackerLaravel\Http\Requests\ConfirmRequest;
use JourneyTracker\JourneyTrackerLaravel\Jobs\ConfirmPageViewJob;
use JourneyTracker\JourneyTrackerLaravel\JourneyTracker;

class ConfirmController
{
    public function __invoke(ConfirmRequest $request, JourneyTracker $journeyTracker): Response
    {
        ConfirmPageViewJob::dispatch($request->toData())->onQueue($journeyTracker->queue());

        return response()->noContent();
    }
}
