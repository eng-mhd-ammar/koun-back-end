<?php

namespace Modules\Donation\Interfaces\V1\DonationItem;

use Modules\Donation\DTO\V1\DonationItemDTO;

interface DonationItemServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(DonationItemDTO $DTO);
    public function update(string $modelId, DonationItemDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
