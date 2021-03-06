<?php

namespace App\Http\ApiV1\Modules\Items\Resources;

use App\Domain\Items\Models\Item;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;

/**
 * @mixin Item
 */
class ExtendedItemsResource extends BaseJsonResource
{
    protected $shipments;

    public function addShipments($shipments)
    {
        $this->shipments = $shipments;
        return $this;
    }


    public function toArray($request)
    {
        $included = $request->included;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'shipments' => $this->shipments,
        ];
    }
}
