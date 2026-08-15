<?php

namespace Modules\Donation\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Auth\Models\User;
use Modules\Donation\Enums\DonationStatus;
use Modules\Donation\Models\Donation;
use Modules\Institution\Models\Branch;

class IsApprovedDonation implements ValidationRule
{
    /**
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate($attribute, $value, $fail): void
    {
        if (is_null($value)) {
            return;
        }

        $donation = Donation::findOrFail($value);

        if (!$donation->status != DonationStatus::APPROVED) {
            $fail('The Donation Does Not Approved.');
        }
    }
}
