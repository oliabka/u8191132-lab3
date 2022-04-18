<?php

namespace App\Http\ApiV1\Modules\Items\Requests;
use App\Http\ApiV1\Support\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;
class GetItemRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $id = (int) $this->route('id');
        return [
            'name' => [Rule::unique('items')->ignore($id)],
            'description' => ['nullable', 'string'],
            'amount' => ['nullable', 'integer'],
        ];
    }
}
