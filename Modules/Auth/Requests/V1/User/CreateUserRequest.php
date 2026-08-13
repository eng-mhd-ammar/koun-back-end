<?php

namespace Modules\Auth\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Enums\Gender;
use Modules\Auth\Models\User;
use Modules\Core\Rules\EnumRule;
use Modules\Core\Rules\FileOrUrl;
use Modules\Core\Rules\UniqueNotDeleted;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'avatar' => [new FileOrUrl(['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'tif', 'webp', 'heic', 'heif', 'svg'])],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'username' => ['required', 'string', new UniqueNotDeleted(User::class, 'username')],
            'birthday' => ['date'],
            'phone' => ['required', 'string', new UniqueNotDeleted(User::class, 'phone'), 'regex:/^\+9639\d{8}$/'],
            'email' => ['required', 'email', new UniqueNotDeleted(User::class, 'email')],
            'password' => ['required', 'string', 'min:8', 'max:20'],
            'gender' => ['integer', new EnumRule(Gender::class), 'default:1'],

            'roles' => ['array', 'nullable'],
            'roles.*' => ['integer', 'exists:roles,id']
        ];
    }
}
