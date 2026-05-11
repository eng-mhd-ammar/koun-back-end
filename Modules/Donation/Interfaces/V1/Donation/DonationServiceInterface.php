<?php

namespace Modules\Donation\Interfaces\V1\Donation;

use Modules\Donation\DTO\V1\DonationDTO;

interface DonationServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(DonationDTO $DTO);
    public function update(string $modelId, DonationDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
