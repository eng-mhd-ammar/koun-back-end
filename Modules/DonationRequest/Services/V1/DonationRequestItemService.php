<?php

namespace Modules\DonationRequest\Services\V1;

use Modules\DonationRequest\Interfaces\V1\DonationRequestItem\DonationRequestItemRepositoryInterface;
use Modules\DonationRequest\Interfaces\V1\DonationRequestItem\DonationRequestItemServiceInterface;
use Modules\Core\Services\BaseService;

class DonationRequestItemService extends BaseService implements DonationRequestItemServiceInterface
{
    public function __construct(protected DonationRequestItemRepositoryInterface $repository)
    {
    }
}
