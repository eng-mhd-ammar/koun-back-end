<?php

namespace Modules\DonationRequest\Rules;

use Closure;
use Modules\Auth\Models\User;
use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Models\DonationRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Donation\Models\DonationItem;

class LessThanRequested implements ValidationRule
{
    /**
     * Cached donation items (optional performance optimization)
     */
    protected array $donationItems = [];

    public function __construct(array $donationItems = [])
    {
        $this->donationItems = $donationItems;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_null($value)) {
            return;
        }

        preg_match('/items\.(\d+)\./', $attribute, $matches);
        $index = $matches[1] ?? null;

        if ($index === null) {
            return;
        }

        $requestData = request()->input();

        $requestedQuantity = data_get($requestData, "items.$index.requested_quantity");
        $donationItemId = data_get($requestData, "items.$index.donation_item_id");

        if ($requestedQuantity === null || $donationItemId === null) {
            return;
        }

        $donationItem = $this->donationItems[$donationItemId]
            ?? DonationItem::query()->find($donationItemId);

        if (!$donationItem) {
            $fail('Invalid donation item.');
            return;
        }

        $availableStock = $donationItem->quantity;


        if ($value > $requestedQuantity) {
            $fail('The value cannot be greater than requested quantity.');
        }

        if ($value > $availableStock) {
            $fail('The value cannot be greater than available stock.');
        }
    }
}
