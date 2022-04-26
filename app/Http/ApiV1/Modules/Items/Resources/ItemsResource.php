<?php

namespace App\Http\ApiV1\Modules\Items\Resources;

use App\Domain\Items\Models\Item;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin Item
 */
class ItemsResource extends BaseJsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
        ];
    }
}
