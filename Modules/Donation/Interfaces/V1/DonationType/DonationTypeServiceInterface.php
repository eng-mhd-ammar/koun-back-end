<?php

namespace Modules\Donation\Interfaces\V1\DonationType;

use Modules\Donation\DTO\V1\DonationTypeDTO;

interface DonationTypeServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(DonationTypeDTO $DTO);
    public function update(string $modelId, DonationTypeDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
