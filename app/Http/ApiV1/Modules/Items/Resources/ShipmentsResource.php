<?php

namespace App\Http\ApiV1\Modules\Items\Resources;

use App\Domain\Shipments\Models\Shipment;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin Shipment
 */
class ShipmentsResource extends BaseJsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'supplier' => $this->supplier,
            'amount' => $this->amount,
            'date_time' => $this->date_time,
            'item_id' => $this->item_id,
        ];
    }
}
