<?php

namespace App\Http\ApiV1\Modules\Items\Resources;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;
class ItemsResource  extends BaseJsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject' => $this->subject,
            'amount' => $this->amount,
        ];
    }
}
