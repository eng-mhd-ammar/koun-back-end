<?php

namespace Modules\DonationRequest\Interfaces\V1\Delivery;

use Modules\DonationRequest\DTO\V1\DeliveryDTO;

interface DeliveryServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(DeliveryDTO $DTO);
    public function update(string $modelId, DeliveryDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
