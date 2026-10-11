<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use JourneyTracker\JourneyTrackerLaravel\DataObjects\QueuedEventData;
use JourneyTracker\JourneyTrackerLaravel\Enums\IngestType;
use JourneyTracker\JourneyTrackerLaravel\Concerns\SendsToJourneyTracker;

class LogPageEventJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SendsToJourneyTracker;

    public function __construct(protected QueuedEventData $data)
    {
        //
    }

    public function handle(): void
    {
        $this->send(IngestType::EVENT, '/api/v1/event', $this->data->toArray());
    }
}
