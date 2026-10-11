<?php

declare(strict_types=1);

use JourneyTracker\JourneyTrackerLaravel\JourneyTracker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Symfony\Component\Finder\Finder;

arch('the package declares strict types throughout')
    ->expect('JourneyTracker\JourneyTrackerLaravel')
    ->toUseStrictTypes();

arch('data objects are immutable value objects')
    ->expect('JourneyTracker\JourneyTrackerLaravel\DataObjects')
    ->toBeFinal()
    ->toBeReadonly();

arch('data objects stay behind the layers that queue them')
    ->expect('JourneyTracker\JourneyTrackerLaravel\DataObjects')
    ->toOnlyBeUsedIn([
        'JourneyTracker\JourneyTrackerLaravel\DataObjects',
        'JourneyTracker\JourneyTrackerLaravel\Http',
        'JourneyTracker\JourneyTrackerLaravel\Jobs',
        JourneyTracker::class,
    ]);

arch('events are immutable value objects')
    ->expect('JourneyTracker\JourneyTrackerLaravel\Events')
    ->toBeFinal()
    ->toBeReadonly();

arch('every job is queueable')
    ->expect('JourneyTracker\JourneyTrackerLaravel\Jobs')
    ->toImplement(ShouldQueue::class);

arch('event types are string backed')
    ->expect('JourneyTracker\JourneyTrackerLaravel\Enums')
    ->toBeStringBackedEnums();

it('declares no mutable static properties anywhere in src', function (): void {
    $sourcePath = dirname(__DIR__, 2) . '/src';

    $offenders = [];

    foreach (Finder::create()->files()->in($sourcePath)->name('*.php') as $file) {
        $relativePath = str_replace([$sourcePath . DIRECTORY_SEPARATOR, '.php'], '', (string) $file->getRealPath());

        /** @var class-string $class */
        $class = 'JourneyTracker\JourneyTrackerLaravel\\' . str_replace(DIRECTORY_SEPARATOR, '\\', $relativePath);

        $reflection = new ReflectionClass($class);

        foreach ($reflection->getProperties(ReflectionProperty::IS_STATIC) as $property) {
            if ($property->getDeclaringClass()->getName() !== $class) {
                continue;
            }

            $offenders[] = $class . '::$' . $property->getName();
        }
    }

    expect($offenders)->toBeEmpty();
});
