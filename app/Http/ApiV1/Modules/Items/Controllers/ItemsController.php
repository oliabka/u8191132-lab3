<?php

namespace App\Http\ApiV1\Modules\Items\Controllers;

use App\Domain\Items\Actions\PostItemAction;
use App\Domain\Items\Actions\DeleteItemAction;
use App\Domain\Items\Actions\PatchItemAction;
use App\Domain\Items\Actions\PutItemAction;
use App\Domain\Items\Actions\GetItemAction;
use App\Domain\Shipments\Models\Shipment;
use App\Http\ApiV1\Modules\Items\Requests\PutItemRequest;
use App\Http\ApiV1\Modules\Items\Requests\PostItemRequest;
use App\Http\ApiV1\Modules\Items\Requests\PatchItemRequest;
use App\Http\ApiV1\Modules\Items\Resources\ExtendedItemsResource;
use App\Http\ApiV1\Modules\Items\Resources\ItemsResource;
use App\Http\ApiV1\Modules\Items\Resources\ShipmentsResource;
use App\Http\ApiV1\Support\Resources\BaseJsonResource;
use App\Http\Controllers\Controller;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function post(PostItemRequest $request,
                         PostItemAction  $action
    ): ItemsResource
    {
        return new ItemsResource
        ($action->execute($request->validated()));
    }

    public function patch(int              $id,
                          PatchItemRequest $request,
                          PatchItemAction  $action
    ): ItemsResource
    {
        return new ItemsResource(
            $action->execute($id, $request->validated())
        );
    }

    public function put(int            $id,
                        PutItemRequest $request,
                        PutItemAction  $action
    ): ItemsResource
    {
        return new ItemsResource(
            $action->execute($id, $request->validated())
        );
    }

    public function delete(int $id, DeleteItemAction $action): JsonResponse
    {
        $action->execute($id);
        return response()->json(["data" => null]);
    }

    public function get(int $id, GetItemAction $action, Request $request): BaseJsonResource
    {
        $shipments = array();
        foreach (Shipment::where('item_id', $id)->get() as $shipment) {
            $shipments[] = new ShipmentsResource($shipment);
        }
        if ($request->get('include')) {
            return (new ExtendedItemsResource($action->execute($id)))->addShipments($shipments);
        } else {
            return new ItemsResource($action->execute($id));
        }
    }
}
