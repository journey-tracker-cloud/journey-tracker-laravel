<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel;

use JourneyTracker\JourneyTrackerLaravel\DataObjects\QueuedTagData;
use JourneyTracker\JourneyTrackerLaravel\Jobs\AssignTagJob;
use JourneyTracker\JourneyTrackerLaravel\Query\QueryBuilder;
use JourneyTracker\JourneyTrackerLaravel\Support\TrackedRequest;
use JourneyTracker\JourneyTrackerLaravel\Support\TrackerScript;
use JourneyTracker\JourneyTrackerLaravel\Support\VisitKey;

class JourneyTracker
{
    public function __construct(
        protected TrackedRequest $trackedRequest,
        protected TrackerScript $trackerScript,
        protected VisitKey $visitKey,
    ) {
        //
    }

    public function isTracking(): bool
    {
        return $this->trackedRequest->isTracking();
    }

    public function visitId(): ?string
    {
        return $this->trackedRequest->visitId();
    }

    public function token(): ?string
    {
        return $this->trackedRequest->visitToken();
    }

    public function tag(string $tag): void
    {
        $visitId = $this->visitId() ?? $this->visitKey->stored()?->id;

        if ($visitId === null) {
            return;
        }

        AssignTagJob::dispatch(new QueuedTagData($visitId, $tag))->onQueue($this->queue());
    }

    public function query(): QueryBuilder
    {
        return new QueryBuilder();
    }

    public function heartbeatScript(): string
    {
        return $this->trackerScript->render();
    }

    /**
     * @internal
     */
    public function queue(): ?string
    {
        $queue = config('journey-tracker-laravel.queue');

        return is_string($queue) ? $queue : null;
    }
}
