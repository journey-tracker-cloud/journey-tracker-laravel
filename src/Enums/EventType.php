<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Enums;

enum EventType: string
{
    case SCROLLED_INTO_VIEW = 'scrolled_into_view';
    case TYPED = 'typed';
    case CLICKED = 'clicked';
    case OTHER = 'other';
}
