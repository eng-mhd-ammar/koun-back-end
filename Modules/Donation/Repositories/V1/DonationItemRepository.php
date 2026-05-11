<?php

namespace Modules\Donation\Repositories\V1;

use Modules\Donation\Interfaces\V1\DonationItem\DonationItemRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;
use Modules\Donation\Models\DonationItem;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class DonationItemRepository extends BaseRepository implements DonationItemRepositoryInterface
{
    protected $model = DonationItem::class;

    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('donation_item', 'id'),
            AllowedFilter::exact('unit', 'unit_id'),
            AllowedFilter::exact('donation', 'donation_id'),
        ];
    }

    public function allowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('donation.sender_user', 'donation.senderUser'),
            AllowedInclude::relationship('donation.receiver_user', 'donation.receiverUser'),
            AllowedInclude::relationship('donation.sender_branch.institution', 'donation.senderBranch.institution'),
            AllowedInclude::relationship('donation.receiver_branch.institution', 'donation.receiverBranch.institution'),
            AllowedInclude::relationship('donation_request_items.donation_request', 'donationRequestItems.donationRequest'),
            AllowedInclude::relationship('unit'),
            AllowedInclude::relationship('donation-type', 'donationType'),
        ];
    }
}
