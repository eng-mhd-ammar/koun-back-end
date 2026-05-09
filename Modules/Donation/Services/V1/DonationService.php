<?php

namespace Modules\Donation\Services\V1;

use DB;
use Modules\Core\DTO\BaseDTO;
use Modules\Donation\Interfaces\V1\Donation\DonationRepositoryInterface;
use Modules\Donation\Interfaces\V1\Donation\DonationServiceInterface;
use Modules\Core\Services\BaseService;
use Modules\Donation\Models\DonationItem;
use Override;

class DonationService extends BaseService implements DonationServiceInterface
{
    public function __construct(protected DonationRepositoryInterface $repository)
    {
    }

    #[Override]
    public function create($DTO)
    {
        $donation = parent::create($DTO);
        $donation->donationItems()->createMany($DTO->items);
        return $donation;
    }

    #[Override]
    public function update(string $modelId, $DTO)
    {
        $data = is_array($DTO) ? $DTO : (array) $DTO;
        $items = $data['items'] ?? [];

        $donation = null;

        DB::transaction(function () use (&$donation, $modelId, $DTO, $items) {

            // =========================
            // Update donation
            // =========================
            $donation = parent::update($modelId, $DTO);

            $itemIds = collect($items)->pluck('id')->filter()->values();

            // =========================
            // Load existing items
            // =========================
            $existingItems = DonationItem::where('donation_id', $donation->id)
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
                        'unit_id'          => $item['unit_id'],
                        'donation_type_id' => $item['donation_type_id'],
                        'name'             => $item['name'],
                        'description'      => $item['description'] ?? null,
                        'quantity'         => $item['quantity'],
                        'notes'            => $item['notes'],
                    ]);

                    continue;
                }

                // CREATE
                $newItems[] = [
                    'donation_id'      => $donation->id,
                    'unit_id'          => $item['unit_id'],
                    'donation_type_id' => $item['donation_type_id'],
                    'name'             => $item['name'],
                    'description'      => $item['description'] ?? null,
                    'quantity'         => $item['quantity'],
                    'notes'            => $item['notes'],
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }

            // =========================
            // DELETE missing items (SYNC FIX)
            // =========================
            DonationItem::where('donation_id', $donation->id)
                ->whereNotIn('id', $itemIds)
                ->delete();

            // =========================
            // Bulk insert
            // =========================
            if (!empty($newItems)) {
                DonationItem::insert($newItems);
            }
        });

        return $donation;
    }
}
