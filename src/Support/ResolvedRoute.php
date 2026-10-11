<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Support;

final readonly class ResolvedRoute
{
    public function __construct(public ?string $name = null, public ?string $uri = null)
    {
        //
    }
}
