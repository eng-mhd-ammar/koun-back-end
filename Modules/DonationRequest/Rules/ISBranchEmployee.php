<?php

namespace Modules\DonationRequest\Rules;

use Modules\Auth\Models\User;
use Modules\DonationRequest\Models\Delivery;
use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Models\DonationRequest;

class ISBranchEmployee
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

        $user = User::query()->findOrFail($value);

        if(request()->input('donation_request_id')) {
            $donationRequest = DonationRequest::query()->findOrFail(request()->input('donation_request_id'));

            if (!$donationRequest) {
                $fail('The selected donation request does not exist.');
            }
        } else {
            $donationRequest = Delivery::query()->findOrFail(request()->route('ModelId'))?->donationRequest;

            if (!$donationRequest) {
                $fail('The selected delivery does not exist.');
            }
        }

        if($donationRequest->receiverBranch->isEmployee($user)) {
            return;
        }

        $fail('The selected user is not an employee of the receiver branch.');
    }
}
