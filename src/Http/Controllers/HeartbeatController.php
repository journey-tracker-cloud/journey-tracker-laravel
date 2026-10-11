<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Http\Controllers;

use Illuminate\Http\Response;
use JourneyTracker\JourneyTrackerLaravel\Http\Requests\HeartbeatRequest;
use JourneyTracker\JourneyTrackerLaravel\Jobs\LogPageViewJob;
use JourneyTracker\JourneyTrackerLaravel\JourneyTracker;
use JourneyTracker\JourneyTrackerLaravel\Support\RouteResolver;
use JourneyTracker\JourneyTrackerLaravel\Support\TrackedQuery;
use JourneyTracker\JourneyTrackerLaravel\Support\TrackingPolicy;

class HeartbeatController
{
    public function __invoke(
        HeartbeatRequest $request,
        TrackingPolicy $trackingPolicy,
        JourneyTracker $journeyTracker,
        RouteResolver $routeResolver,
        TrackedQuery $trackedQuery,
    ): Response {
        $data = $request->toData($routeResolver, $trackedQuery);

        if ($trackingPolicy->shouldTrackPath($data->path, $data->route, $data->routePath)) {
            LogPageViewJob::dispatch($data)->onQueue($journeyTracker->queue());
        }

        return response()->noContent();
    }
}
