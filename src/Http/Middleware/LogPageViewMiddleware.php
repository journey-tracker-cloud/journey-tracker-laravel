<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JourneyTracker\JourneyTrackerLaravel\DataObjects\QueuedPageViewData;
use JourneyTracker\JourneyTrackerLaravel\Jobs\LogPageViewJob;
use JourneyTracker\JourneyTrackerLaravel\JourneyTracker;
use JourneyTracker\JourneyTrackerLaravel\Support\TrackedQuery;
use JourneyTracker\JourneyTrackerLaravel\Support\TrackedRequest;
use JourneyTracker\JourneyTrackerLaravel\Support\TrackingPolicy;
use Symfony\Component\HttpFoundation\Response;

class LogPageViewMiddleware
{
    public function __construct(
        protected JourneyTracker $journeyTracker,
        protected TrackedRequest $trackedRequest,
        protected TrackingPolicy $trackingPolicy,
        protected TrackedQuery $trackedQuery,
    ) {
        //
    }

    /**
     * @deprecated Use JourneyTracker::token(), or the JourneyTracker facade.
     */
    public static function getToken(): ?string
    {
        return app(JourneyTracker::class)->token();
    }

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->trackedRequest->observe();

        if ($this->trackingPolicy->shouldTrackRequest($request)) {
            $this->trackedRequest->start();
        }

        $response = $next($request);

        $visitId = $this->trackedRequest->visitId();

        if ($visitId !== null) {
            $this->trackedRequest->persistVisit();

            LogPageViewJob::dispatch(new QueuedPageViewData(
                $visitId,
                $request->path(),
                $request->route()?->getName(),
                $request->route()?->uri(),
                time(),
                $request->userAgent(),
                $this->trackedRequest->visitKeyWasNew(),
                $this->trackedRequest->confirmationExpected(),
                $this->trackedQuery->filter($request->query()),
                $request->header('Sec-Fetch-Mode'),
                $request->header('Sec-Fetch-Dest'),
                $request->header('Sec-Fetch-User'),
            ))->onQueue($this->journeyTracker->queue());
        }

        $token = $this->trackedRequest->visitToken();

        if ($token !== null) {
            $response->headers->set('X-Journey-Token', $token);
        }

        return $response;
    }
}
