<?php

namespace Modules\DonationRequest\Repositories\V1;

use Modules\DonationRequest\Interfaces\V1\DonationRequestItem\DonationRequestItemRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;
use Modules\DonationRequest\Models\DonationRequestItem;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class DonationRequestItemRepository extends BaseRepository implements DonationRequestItemRepositoryInterface
{
    protected $model = DonationRequestItem::class;

    public function allowedFilters(): array
    {
        return [
            // AllowedFilter::exact('delivery', 'id'),
            // AllowedFilter::exact('delivery', 'delivery_id'),
            // AllowedFilter::exact('receiver', 'receiver_id'),
            // AllowedFilter::exact('donation_request', 'donation_request_id'),
        ];
    }

    public function allowedIncludes(): array
    {
        return [
            // AllowedInclude::relationship('delivery'),
            // AllowedInclude::relationship('receiver'),
            // AllowedInclude::relationship('donation_request', 'donationRequest'),
        ];
    }
}
