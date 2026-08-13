<?php

namespace Modules\Auth\Requests\V1\User;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Auth\Enums\Gender;
use Modules\Auth\Models\User;
use Modules\Core\Rules\EnumRule;
use Modules\Core\Rules\FileOrUrl;
use Modules\Core\Rules\UniqueNotDeleted;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'avatar' => [new FileOrUrl(['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'tif', 'webp', 'heic', 'heif', 'svg'])],
            'first_name' => ['string'],
            'last_name' => ['string'],
            'username' => ['string', new UniqueNotDeleted(User::class, 'username', $this->route('modelId'))],
            'birthday' => ['date'],
            'phone' => ['string', new UniqueNotDeleted(User::class, 'phone', $this->route('modelId')), 'regex:/^\+9639\d{8}$/'],
            'email' => ['email', new UniqueNotDeleted(User::class, 'email', $this->route('modelId'))],
            'password' => ['string', 'min:8', 'max:20'],
            'gender' => ['integer', new EnumRule(Gender::class), 'default:1'],

            'roles' => ['array',  'exists:roles,id', 'default:2'],
        ];
    }
}
