<?php

namespace Modules\Address\Interfaces\V1\Address;

use Modules\Address\DTO\V1\AddressDTO;

interface AddressServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(AddressDTO $DTO);
    public function update(string $modelId, AddressDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
