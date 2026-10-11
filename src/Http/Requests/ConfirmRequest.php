<?php

declare(strict_types=1);

namespace JourneyTracker\JourneyTrackerLaravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use JourneyTracker\JourneyTrackerLaravel\DataObjects\QueuedConfirmationData;
use JourneyTracker\JourneyTrackerLaravel\Rules\DecryptableToken;
use JourneyTracker\JourneyTrackerLaravel\Support\JourneyToken;

class ConfirmRequest extends FormRequest
{
    /** @return array<string, list<mixed>> */
    public function rules(DecryptableToken $decryptableToken): array
    {
        return [
            'token' => ['required', 'string', $decryptableToken],
        ];
    }

    public function toData(): QueuedConfirmationData
    {
        $token = JourneyToken::decrypt($this->string('token')->toString());

        return new QueuedConfirmationData(visitId: $token->visitId);
    }
}
