<?php

namespace Modules\DonationRequest\Observers;

use DB;
use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Models\DonationRequest;

class DonationRequestObserver
{
    public function created(DonationRequest $donationRequest): void
    {
        $this->handleStockUpdate($donationRequest);
    }

    public function updated(DonationRequest $donationRequest): void
    {
        if ($donationRequest->wasChanged('status') && $donationRequest->status == DonationRequestStatus::APPROVED) {
            $this->handleStockUpdate($donationRequest);
        }
    }

    private function handleStockUpdate(DonationRequest $donationRequest): void
    {
        if ($donationRequest->status != DonationRequestStatus::APPROVED) {
            return;
        }

        DB::transaction(function () use ($donationRequest) {

            $items = $donationRequest->items()->with('donationItem')->get();

            foreach ($items as $item) {

                $donationItem = $item->donationItem;

                if (!$donationItem) {
                    continue;
                }

                $quantityToDeduct = $item->approved_quantity ?? $item->requested_quantity;

                $donationItem->decrement('remaining_quantity', $quantityToDeduct);
            }
        });
    }
}
