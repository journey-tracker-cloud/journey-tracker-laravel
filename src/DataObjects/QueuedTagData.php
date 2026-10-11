<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\DataObjects;

final readonly class QueuedTagData
{
    public function __construct(
        public string $visitId,
        public string $tag,
    ) {
        //
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'session_id' => $this->visitId,
            'tag' => $this->tag,
        ];
    }
}
