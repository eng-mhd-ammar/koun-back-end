<?php

namespace Modules\DonationRequest\Interfaces\V1\DonationRequest;

use Modules\DonationRequest\DTO\V1\DonationRequestDTO;

interface DonationRequestServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(DonationRequestDTO $DTO);
    public function update(string $modelId, DonationRequestDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
