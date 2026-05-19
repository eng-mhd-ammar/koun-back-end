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
use Modules\DonationRequest\Rules\IsCharity;
use Modules\Institution\Models\Branch;

class CreateDonationRequestRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'receiver_branch_id' => ['nullable', 'string', new NotSoftDeleted(Branch::class), new IsCharity],
            'receiver_user_id' => ['required', 'string', new NotSoftDeleted(User::class)],
            'status' => ['required', 'numeric', new EnumRule(DonationRequestStatus::class), 'default:' . DonationRequestStatus::PENDING->value, new ProhibitedUnlessHasRole(['admin'], DonationRequestStatus::PENDING->value)],
            'notes' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.donation_request_id' => ['required', new NotSoftDeleted(DonationRequest::class)],
            'items.*.donation_item_id' => ['required', new NotSoftDeleted(DonationItem::class)],
            'items.*.requested_quantity' => ['required', 'numeric', 'min:1', 'default:1'],
            'items.*.approved_quantity' => ['nullable', 'numeric', 'min:0', new ProhibitedUnlessHasRole(['admin'])],
            'items.*.received_quantity' => ['nullable', 'numeric', 'min:0', 'default:0', new ProhibitedUnlessHasRole(['admin'], 0)],
        ];
    }
}
