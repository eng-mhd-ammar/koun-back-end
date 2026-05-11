<?php

namespace Modules\DonationRequest\Rules;

use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Models\DonationRequest;

class DonationRequestIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function validate($attribute, $value, $fail): void
    {
        if (is_null($value)) {
            return;
        }

        $donationRequest = DonationRequest::query()->findOrFail($value);

        if (!$donationRequest->status != DonationRequestStatus::APPROVED) {
            $fail('The selected institution is not a charity.');
        }
    }
}
