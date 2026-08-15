<?php

namespace Modules\Donation\Requests\V1\Unit;

use Illuminate\Foundation\Http\FormRequest;

class CreateUnitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required_without:units', 'string', 'max:255'],
            'description' => ['required_without:units', 'string'],

            'units' => ['required_without:name', 'array'],
            'units.*.name' => ['required', 'string', 'max:255'],
            'units.*.description' => ['string', 'max:255'],
        ];
    }
}
