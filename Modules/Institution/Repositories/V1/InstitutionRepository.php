<?php

namespace Modules\Institution\Repositories\V1;

use Modules\Institution\Interfaces\V1\Institution\InstitutionRepositoryInterface;
use Modules\Core\Repositories\BaseRepository;
use Modules\Institution\Models\Institution;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;

class InstitutionRepository extends BaseRepository implements InstitutionRepositoryInterface
{
    protected $model = Institution::class;

    public function allowedFilters(): array
    {
        return [
            AllowedFilter::exact('institution', 'id'),
            AllowedFilter::exact('owner', 'owner_id'),
            AllowedFilter::exact('active', 'is_active'),
            AllowedFilter::scope('for_user', 'forUser')->default([1]),
        ];
    }

    public function allowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('owner'),
            AllowedInclude::relationship('branches.address'),
            AllowedInclude::relationship('branches.donations.donation_items', 'branches.donations.donationItems'),
            AllowedInclude::relationship('branches.donation_requests.donation_request_items', 'branches.donationsRequests.donationRequestItems'),
            AllowedInclude::relationship('user_institutions', 'userInstitutions'),
            AllowedInclude::relationship('members'),
        ];
    }
}
