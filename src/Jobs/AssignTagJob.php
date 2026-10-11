<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use JourneyTracker\JourneyTrackerLaravel\DataObjects\QueuedTagData;
use JourneyTracker\JourneyTrackerLaravel\Enums\IngestType;
use JourneyTracker\JourneyTrackerLaravel\Concerns\SendsToJourneyTracker;

class AssignTagJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SendsToJourneyTracker;

    public function __construct(protected QueuedTagData $data)
    {
        //
    }

    public function handle(): void
    {
        $this->send(IngestType::TAG, '/api/v1/tag', $this->data->toArray());
    }
}
