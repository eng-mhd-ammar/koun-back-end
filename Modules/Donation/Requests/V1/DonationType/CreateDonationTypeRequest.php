<?php

namespace Modules\Donation\Requests\V1\DonationType;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\NotSoftDeleted;
use Modules\Donation\Models\DonationType;

class CreateDonationTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required_without:types', 'string', 'max:255'],
            'parent_id' => ['nullable', 'string', new NotSoftDeleted(DonationType::class)],

            'types' => ['required_without:name', 'array'],
            'types.*.name' => ['required', 'string', 'max:255'],
            'types.*.parent_id' => ['nullable', 'string', new NotSoftDeleted(DonationType::class)],
        ];
    }
}
