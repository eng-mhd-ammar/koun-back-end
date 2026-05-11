<?php

namespace Modules\DonationRequest\Services\V1;

use Modules\DonationRequest\Interfaces\V1\Delivery\DeliveryRepositoryInterface;
use Modules\DonationRequest\Interfaces\V1\Delivery\DeliveryServiceInterface;
use Modules\Core\Services\BaseService;

class DeliveryService extends BaseService implements DeliveryServiceInterface
{
    public function __construct(protected DeliveryRepositoryInterface $repository)
    {
    }
}
