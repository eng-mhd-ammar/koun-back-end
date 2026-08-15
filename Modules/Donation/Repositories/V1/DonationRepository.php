<?php

namespace Modules\Donation\Repositories\V1;

use Modules\Donation\Interfaces\V1\Donation\DonationRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;
use Modules\Donation\Models\Donation;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class DonationRepository extends BaseRepository implements DonationRepositoryInterface
{
    protected $model = Donation::class;

    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('donation', 'id'),

            AllowedFilter::exact('sender_user', 'sender_user_id'),

            AllowedFilter::exact('sender_branch', 'sender_branch_id'),

            AllowedFilter::exact('sender_institution', 'sender_branch.institution_id'),
        ];
    }

    public function allowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('sender_user', 'senderUser'),
            AllowedInclude::relationship('sender_branch.institution', 'senderBranch.institution'),
            AllowedInclude::relationship('donation_items.unit', 'donationItems.unit'),
            AllowedInclude::relationship('donation_items.donation_type', 'donationItems.donationType'),
        ];
    }
}
