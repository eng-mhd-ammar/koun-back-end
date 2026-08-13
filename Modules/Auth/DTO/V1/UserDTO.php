<?php

namespace Modules\Auth\DTO\V1;

use Carbon\Carbon;
use Modules\Auth\Requests\V1\User\CreateUserRequest;
use Modules\Auth\Requests\V1\User\UpdateUserRequest;
use Modules\Core\DTO\BaseDTO;

class UserDTO extends BaseDTO
{
    public function __construct(
        public ?string $avatar,
        public ?string $first_name,
        public ?string $last_name,
        public ?string $username,
        public ?string $phone,
        public ?string $email,
        public ?string $password,
        public ?Carbon $birthday,
        public ?bool $gender,
        public ?array $roles,
    ) {
    }

    public static function fromRequest(CreateUserRequest|UpdateUserRequest $request): self
    {
        return new self(
            avatar: self::handleFileStoring($request->validated('avatar'), '/avatars'),
            first_name: $request->validated('first_name'),
            last_name: $request->validated('last_name'),
            username: $request->validated('username'),
            phone: $request->validated('phone'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            birthday: self::prepareDateTime($request->validated('birthday')),
            gender: $request->validated('gender'),
            roles: $request->validated('roles'),

        );
    }
}
