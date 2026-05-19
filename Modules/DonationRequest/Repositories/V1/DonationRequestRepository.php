<?php

namespace Modules\DonationRequest\Repositories\V1;

use Modules\DonationRequest\Interfaces\V1\DonationRequest\DonationRequestRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;
use Modules\DonationRequest\Models\DonationRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class DonationRequestRepository extends BaseRepository implements DonationRequestRepositoryInterface
{
    protected $model = DonationRequest::class;

    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('donation_request', 'id'),
            AllowedFilter::exact('receiver_branch', 'receiver_branch_id'),
            AllowedFilter::exact('receiver_user', 'receiver_user_id'),
        ];
    }

    public function allowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('receiver_branch.institution.owner', 'receiverBranch.institution.owner'),
            AllowedInclude::relationship('receiver_user', 'receiverUser'),
        ];
    }
}
