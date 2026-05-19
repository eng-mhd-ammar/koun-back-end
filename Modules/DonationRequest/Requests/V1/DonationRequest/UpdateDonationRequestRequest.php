<?php

namespace Modules\DonationRequest\Requests\V1\DonationRequest;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Models\User;
use Modules\Core\Rules\EnumRule;
use Modules\Core\Rules\NotSoftDeleted;
use Modules\Core\Rules\ProhibitedUnlessHasRole;
use Modules\Donation\Models\DonationItem;
use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Models\DonationRequest;
use Modules\DonationRequest\Models\DonationRequestItem;
use Modules\DonationRequest\Rules\IsCharity;
use Modules\Institution\Models\Branch;

class UpdateDonationRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receiver_branch_id' => ['nullable', 'string', new NotSoftDeleted(Branch::class), new IsCharity],
            'receiver_user_id' => ['nullable', 'string', new NotSoftDeleted(User::class)],
            'status' => ['nullable', 'numeric', new EnumRule(DonationRequestStatus::class), new ProhibitedUnlessHasRole(['admin'], DonationRequestStatus::PENDING->value)],
            'notes' => ['nullable', 'string'],

            'items' => ['array', 'min:1'],
            'items.*.id' => ['string', new NotSoftDeleted(DonationRequestItem::class)],
            'items.*.donation_request_id' => ['required', new NotSoftDeleted(DonationRequest::class)],
            'items.*.donation_item_id' => ['required', new NotSoftDeleted(DonationItem::class)],
            'items.*.requested_quantity' => ['required', 'numeric', 'min:1', 'default:1'],
            'items.*.approved_quantity' => ['nullable', 'numeric', 'min:0', new ProhibitedUnlessHasRole(['admin'])],
            'items.*.received_quantity' => ['nullable', 'numeric', 'min:0', 'default:0', new ProhibitedUnlessHasRole(['admin'], 0)],
        ];
    }
}
