<?php

namespace Modules\Address\Requests\V1\State;

use Illuminate\Foundation\Http\FormRequest;

class CreateStateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required_without:names', 'string', 'max:255'],

            'names' => ['required_without:name', 'array'],
            'names.*' => ['string', 'max:255'],
        ];
    }
}
