<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use JourneyTracker\JourneyTrackerLaravel\DataObjects\QueuedPageViewData;
use JourneyTracker\JourneyTrackerLaravel\Enums\IngestType;
use JourneyTracker\JourneyTrackerLaravel\Concerns\SendsToJourneyTracker;
use JourneyTracker\JourneyTrackerLaravel\Support\VisitKey;

class LogPageViewJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SendsToJourneyTracker;

    public function __construct(protected QueuedPageViewData $data)
    {
        //
    }

    public function handle(VisitKey $visitKey): void
    {
        $threshold = $this->send(IngestType::PAGE_VIEW, '/api/v1/page-view', $this->data->toArray())
            ?->json('visit_threshold_minutes');

        if (is_int($threshold)) {
            $visitKey->rememberThreshold($threshold);
        }
    }
}
