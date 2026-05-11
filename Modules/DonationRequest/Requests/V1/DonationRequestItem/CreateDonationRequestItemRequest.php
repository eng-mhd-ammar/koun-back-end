<?php

namespace Modules\DonationRequest\Requests\V1\DonationRequestItem;

use Illuminate\Foundation\Http\FormRequest;

class CreateDonationRequestItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // 'donation_request_id' => ['required', new NotSoftDeleted(DonationRequest::class), new DonationRequestIsApproved()],
            // 'delivery_id' => ['required', 'string', new NotSoftDeleted(User::class), 'default:' . auth()->id(), new ProhibitedUnlessHasRole(['admin'], auth()->id()), new IsDonationRequestItem()],
            // 'receiver_id' => ['required', 'string', new NotSoftDeleted(User::class), new ISBranchEmployee()],
            // 'status' => ['numeric', new EnumRule(DonationRequestItemStatus::class), 'default:' . DonationRequestItemStatus::PENDING->value, new ProhibitedUnlessHasRole(['admin', 'delivery'], DonationRequestItemStatus::PENDING->value)],
            // 'picked_at' => ['nullable', 'date', new ProhibitedUnlessHasRole(['admin', 'delivery'])],
            // 'delivered_at' => ['nullable', 'date', 'after_or_equal:picked_at', new ProhibitedUnlessHasRole(['admin', 'delivery'])],
        ];
    }
}
