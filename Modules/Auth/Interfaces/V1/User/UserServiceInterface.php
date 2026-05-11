<?php

namespace Modules\Auth\Interfaces\V1\User;

use Modules\Auth\DTO\V1\UserDTO;

interface UserServiceInterface
{
    public function index();
    public function show(string $modelId);
    public function create(UserDTO $DTO);
    public function update(string $modelId, UserDTO $DTO);
    public function delete(string $modelId);
    public function ForceDelete(string $modelId);
    public function restore(string $modelId);
}
