<?php

namespace Modules\Institution\Requests\V1\Branch;

use Modules\Auth\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Address\Models\Address;
use Modules\Address\Models\State;
use Modules\Core\Rules\NotSoftDeleted;
use Modules\Core\Rules\ObjectOrMinusOne;
use Modules\Institution\Models\Institution;

class UpdateBranchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['string'],
            'institution_id' => ['required', 'string', new NotSoftDeleted(Institution::class)],
            'phone' => ['required', 'string', 'regex:/^\+9639\d{8}$/'],
            'email' => ['required', 'email'],
            'is_main_branch' => ['boolean'],

            'address.id' => ['string', new NotSoftDeleted(Address::class)],
            'address.state_id' => ['required', 'string', new NotSoftDeleted(State::class)],
            'address.city' => ['required', 'string', 'max:255'],
            'address.street' => ['required', 'string', 'max:255'],
            'address.latitude' => ['required', 'numeric', 'between:-90,90'],
            'address.longitude' => ['required', 'numeric', 'between:-180,180'],
            'address.details' => ['nullable', 'string'],

            'users' => ['required', new ObjectOrMinusOne()],
            'users.*.user_id' => ['required', 'string', new NotSoftDeleted(User::class)],
            'users.*.is_admin' => ['boolean', 'default:0'],
        ];
    }
}
