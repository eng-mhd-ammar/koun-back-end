<?php

namespace Modules\DonationRequest\Rules;

use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Models\DonationRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class DonationRequestIsApproved implements ValidationRule
{
    public function validate($attribute, $value, $fail): void
    {
        if (is_null($value)) {
            return;
        }

        $donationRequest = DonationRequest::query()->findOrFail($value);

        if ($donationRequest->status != DonationRequestStatus::APPROVED) {
            $fail('The Donation Request Does Not Approved.');
        }
    }
}
