<?php

namespace App\Http\ApiV1\Modules\Items\Requests;
use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
class PatchItemRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $id = (int) $this->route('id');
        return [
            'name' => [Rule::unique('items')->ignore($id)],
            'description' => ['string'],
            'amount' => ['integer'],
        ];
    }
}
