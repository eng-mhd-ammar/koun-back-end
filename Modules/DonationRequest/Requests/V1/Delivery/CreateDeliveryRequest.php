<?php

namespace Modules\DonationRequest\Requests\V1\Delivery;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\EnumRule;
use Modules\Core\Rules\NotSoftDeleted;
use Modules\Core\Rules\ProhibitedUnlessHasRole;
use Modules\DonationRequest\Enums\DeliveryStatus;
use Modules\DonationRequest\Rules\DonationRequestIsApproved;
use Modules\DonationRequest\Rules\ISBranchEmployee;
use Modules\DonationRequest\Rules\IsDelivery;
use Modules\DonationRequest\Models\DonationRequest;

class CreateDeliveryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'donation_request_id' => ['required', new NotSoftDeleted(DonationRequest::class), new DonationRequestIsApproved()],
            'delivery_id' => ['required', 'string', new NotSoftDeleted(User::class), 'default:' . auth()->id(), new ProhibitedUnlessHasRole(['admin'], auth()->id()), new IsDelivery()],
            'receiver_id' => ['required', 'string', new NotSoftDeleted(User::class), new ISBranchEmployee()],
            'status' => ['numeric', new EnumRule(DeliveryStatus::class), 'default:' . DeliveryStatus::PENDING->value, new ProhibitedUnlessHasRole(['admin', 'delivery'], DeliveryStatus::PENDING->value)],
            'picked_at' => ['nullable', 'date', new ProhibitedUnlessHasRole(['admin', 'delivery'])],
            'delivered_at' => ['nullable', 'date', 'after_or_equal:picked_at', new ProhibitedUnlessHasRole(['admin', 'delivery'])],
        ];
    }
}
