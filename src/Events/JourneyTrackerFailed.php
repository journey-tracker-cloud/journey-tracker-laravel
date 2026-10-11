<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Events;

use JourneyTracker\JourneyTrackerLaravel\Enums\IngestType;
use Throwable;

final readonly class JourneyTrackerFailed
{
    public function __construct(
        public IngestType $type,
        public ?int $status,
        public Throwable $exception,
    ) {
        //
    }
}
