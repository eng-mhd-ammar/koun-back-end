<?php

namespace Modules\DonationRequest\Services\V1;

use Modules\DonationRequest\Interfaces\V1\DonationRequest\DonationRequestRepositoryInterface;
use Modules\DonationRequest\Interfaces\V1\DonationRequest\DonationRequestServiceInterface;
use Modules\Core\Services\BaseService;

class DonationRequestService extends BaseService implements DonationRequestServiceInterface
{
    public function __construct(protected DonationRequestRepositoryInterface $repository)
    {
    }
}
