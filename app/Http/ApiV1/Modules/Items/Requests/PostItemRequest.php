<?php

namespace App\Http\ApiV1\Modules\Items\Requests;

use App\Http\ApiV1\Support\Requests\BaseFormRequest;

class PostItemRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['string'],
            'amount' => ['integer'],
        ];
    }
}
