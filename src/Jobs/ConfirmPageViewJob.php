<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use JourneyTracker\JourneyTrackerLaravel\DataObjects\QueuedConfirmationData;
use JourneyTracker\JourneyTrackerLaravel\Enums\IngestType;
use JourneyTracker\JourneyTrackerLaravel\Concerns\SendsToJourneyTracker;

class ConfirmPageViewJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SendsToJourneyTracker;

    public function __construct(protected QueuedConfirmationData $data)
    {
        //
    }

    public function handle(): void
    {
        $this->send(IngestType::PAGE_VIEW_CONFIRMATION, '/api/v1/page-view/confirm', $this->data->toArray());
    }
}
