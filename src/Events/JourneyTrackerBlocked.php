<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Events;

use JourneyTracker\JourneyTrackerLaravel\Enums\IngestType;

final readonly class JourneyTrackerBlocked
{
    public function __construct(public IngestType $type)
    {
        //
    }
}
