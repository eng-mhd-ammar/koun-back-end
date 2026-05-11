<?php

namespace Modules\DonationRequest\Requests\V1\DonationRequest;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Models\User;
use Modules\Core\Rules\EnumRule;
use Modules\Core\Rules\NotSoftDeleted;
use Modules\Core\Rules\ProhibitedUnlessHasRole;
use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Models\Donation;
use Modules\DonationRequest\Rules\IsApprovedDonation;
use Modules\DonationRequest\Rules\IsCharity;
use Modules\Institution\Models\Branch;

class CreateDonationRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receiver_branch_id' => ['string', new NotSoftDeleted(Branch::class), new IsCharity],
            'receiver_user_id' => ['required', 'string', new NotSoftDeleted(User::class)],
            'status' => ['required', 'numeric', new EnumRule(DonationRequestStatus::class), 'default:' . DonationRequestStatus::PENDING->value, new ProhibitedUnlessHasRole(['admin', DonationRequestStatus::PENDING->value])],
            'notes' => ['nullable', 'string'],
        ];
    }
}
