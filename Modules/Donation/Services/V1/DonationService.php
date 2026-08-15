<?php

namespace Modules\Donation\Services\V1;

use DB;
use Modules\Core\DTO\BaseDTO;
use Modules\Donation\Interfaces\V1\Donation\DonationRepositoryInterface;
use Modules\Donation\Interfaces\V1\Donation\DonationServiceInterface;
use Modules\Core\Services\BaseService;
use Modules\Donation\Enums\DonationStatus;
use Modules\Donation\Models\Donation;
use Modules\Donation\Models\DonationItem;
use Modules\DonationRequest\Enums\DeliveryStatus;
use Modules\DonationRequest\Models\DonationRequest;
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
                ->ForceDelete();

            // =========================
            // Bulk insert
            // =========================
            if (!empty($newItems)) {
                DonationItem::insert($newItems);
            }
        });

        return $donation;
    }

    public function statistics() {
        $data['total_donations'] = Donation::query()->count();
        $data['active_institutions'] = Donation::query()->where('status', DonationStatus::APPROVED->value)->count();
        $data['pending_donation_requests'] = DonationRequest::query()->where('status', DonationStatus::PENDING->value)->count();
        $data['delivered_donations'] = DonationRequest::query()->whereHas('deliveries', function ($query) {
            $query->where('status', DeliveryStatus::DELIVERED->value);
        })->count();

        $startDate = now()->subDays(29)->startOfDay();

        $donations = Donation::query()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('
                FLOOR(DATEDIFF(created_at, ?) / 5) as period,
                COUNT(*) as count
            ', [$startDate->toDateString()])
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $data['graph'] = collect(range(0, 5))->map(function ($period) use ($donations, $startDate) {
            $start = $startDate->copy()->addDays($period * 5);
            $end = $start->copy()->addDays(4);

            return [
                'period' => $start->format('d/m') . ' - ' . $end->format('d/m'),
                'count' => $donations->firstWhere('period', $period)?->count ?? 0,
            ];
        });

        return $data;
    }
}
