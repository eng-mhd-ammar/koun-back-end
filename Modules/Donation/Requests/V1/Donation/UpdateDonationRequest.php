<?php

namespace Modules\Donation\Requests\V1\Donation;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Models\User;
use Modules\Core\Rules\EnumRule;
use Modules\Core\Rules\NotSoftDeleted;
use Modules\Core\Rules\ProhibitedUnlessHasRole;
use Modules\Donation\Enums\DonationStatus;
use Modules\Donation\Models\DonationItem;
use Modules\Donation\Models\DonationType;
use Modules\Donation\Models\Unit;
use Modules\Donation\Rules\IsDonor;
use Modules\Institution\Models\Branch;

class UpdateDonationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'sender_branch_id' => ['nullable', 'string', new NotSoftDeleted(Branch::class)],
            'sender_user_id' => ['nullable', 'string', new NotSoftDeleted(User::class), new ProhibitedUnlessHasRole(['admin'], auth()->id()), new IsDonor()],

            'title' => ['string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'status' => ['numeric', new EnumRule(DonationStatus::class), new ProhibitedUnlessHasRole(['admin'], DonationStatus::PENDING->value)],
            'sent_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],

            'items' => ['array', 'min:1'],
            'items.*.id' => ['string', new NotSoftDeleted(DonationItem::class)],
            'items.*.unit_id' => ['required', 'string', new NotSoftDeleted(Unit::class)],
            'items.*.donation_type_id' => ['required', 'string', new NotSoftDeleted(DonationType::class)],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
            'items.*.notes' => ['nullable', 'string'],
        ];
    }
}
