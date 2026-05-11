<?php

namespace Modules\DonationRequest\Interfaces\V1\DonationRequestItem;

use Modules\DonationRequest\DTO\V1\DonationRequestItemDTO;

interface DonationRequestItemServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(DonationRequestItemDTO $DTO);
    public function update(string $modelId, DonationRequestItemDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
