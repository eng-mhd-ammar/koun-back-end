<?php

namespace Modules\DonationRequest\Services\V1;

use Modules\DonationRequest\Interfaces\V1\DonationRequest\DonationRequestRepositoryInterface;
use Modules\DonationRequest\Interfaces\V1\DonationRequest\DonationRequestServiceInterface;
use Modules\Core\Services\BaseService;
use Modules\DonationRequest\Models\DonationRequestItem;
use Override;

class DonationRequestService extends BaseService implements DonationRequestServiceInterface
{
    public function __construct(protected DonationRequestRepositoryInterface $repository)
    {
    }

    #[Override]
    public function create($DTO)
    {
        $donationRequest = parent::create($DTO);
        $donationRequest->donationItems()->createMany($DTO->items);
        return $donationRequest;
    }

    #[Override]
    public function update(string $modelId, $DTO)
    {
        $data = is_array($DTO) ? $DTO : (array) $DTO;
        $items = $data['items'] ?? [];

        $donationRequest = null;

        DB::transaction(function () use (&$donationRequest, $modelId, $DTO, $items) {

            // =========================
            // Update donation request
            // =========================
            $donationRequest = parent::update($modelId, $DTO);

            $itemIds = collect($items)->pluck('id')->filter()->values();

            // =========================
            // Load existing items
            // =========================
            $existingItems = DonationRequestItem::where('donation_request_id', $donationRequest->id)
                ->whereIn('id', $itemIds)
                ->get()
                ->keyBy('id');

            $newItems = [];

            // =========================
            // Update / Create
            // =========================
            foreach ($items as $item) {

                // UPDATE
                if (!empty($item['id']) && isset($existingItems[$item['id']])) {

                    $existingItems[$item['id']]->update([
                        'donation_item_id' => $item['donation_item_id'],
                        'requested_quantity' => $item['requested_quantity'],
                        'approved_quantity' => $item['approved_quantity'] ?? 0,
                        'received_quantity' => $item['received_quantity'] ?? 0,
                    ]);

                    continue;
                }

                // CREATE
                $newItems[] = [
                    'donation_request_id' => $donationRequest->id,
                    'donation_item_id'    => $item['donation_item_id'],
                    'requested_quantity'  => $item['requested_quantity'],
                    'approved_quantity'   => $item['approved_quantity'] ?? 0,
                    'received_quantity'   => $item['received_quantity'] ?? 0,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ];
            }

            // =========================
            // DELETE missing items (SYNC FIX)
            // =========================
            DonationRequestItem::where('donation_request_id', $donationRequest->id)
                ->whereNotIn('id', $itemIds)
                ->delete();

            // =========================
            // Bulk insert
            // =========================
            if (!empty($newItems)) {
                DonationRequestItem::insert($newItems);
            }
        });

        return $donationRequest;
    }
}
