<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JourneyTracker\JourneyTrackerLaravel\JourneyTracker
 * @method static void tag(string $tag)
 * @method static string heartbeatScript()
 * @method static \JourneyTracker\JourneyTrackerLaravel\Query\QueryBuilder query()
 * @method static string|null token()
 * @method static string|null visitId()
 * @method static bool isTracking()
 */
class JourneyTracker extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'journey-tracker';
    }
}
