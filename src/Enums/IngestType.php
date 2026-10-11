<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Enums;

enum IngestType: string
{
    case PAGE_VIEW = 'page_view';
    case PAGE_VIEW_CONFIRMATION = 'page_view_confirmation';
    case EVENT = 'event';
    case TAG = 'tag';
}
