<?php

declare(strict_types=1);

use JourneyTracker\JourneyTrackerLaravel\DataObjects\QueuedConfirmationData;

it('serialises to the wire shape the api expects', function (): void {
    expect(new QueuedConfirmationData('visit-abc')->toArray())->toBe([
        'visit_id' => 'visit-abc',
    ]);
});
