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
use Elastic\Elasticsearch\ClientBuilder;
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
        if ($request->get('include') === 'shipments') {
            $shipments = array();
            foreach (Shipment::where('item_id', $id)->get() as $shipment) {
                $shipments[] = new ShipmentsResource($shipment);
            }
            return (new ExtendedItemsResource($action->execute($id)))->addShipments($shipments);
        } else {
            return new ItemsResource($action->execute($id));
        }
    }

    public function getIndex(Request $request): JsonResponse
    {
        $hosts = [
            'http://127.0.0.1:9200',
        ];

        $size = $request->json('page size', 1000);
        $from = $request->json('page', 0) * $size;
        $sort_initial = $request->json('sort by');
        $sort = [];
        foreach ($sort_initial as $key) {
            $sort[] = [$key => ['order' => 'asc']];
        }
        //$sort = [$sort];
        $match = $request->json('match');
        if (!$match) {
            $query = [
                'match_all' => (object)[]
            ];
        } else {
            $match_query = [];
            foreach ($match as $key => $value) {
                $match_query[] = ['match' => [$key => $value]];
            }
            $query = ['bool' => ['must' => $match_query]];
        }

        //$query = ['bool' => ['must' => [['match' => ['amount' => 510]], ['match' => ['name' => 'omnis']]]]];

        $params = [
            'index' => 'items_index',
            'body' => [
                'query' => $query,
                'sort' => [
                    ['description' => ['order' => 'asc']],
                    ['name' => ['order' => 'asc']]
                ],
                'size' => $size,
                'from' => $from,
            ]
        ];//                    'sort' => $sort,
        $client = ClientBuilder::create()->setHosts($hosts)->build();
        $response = $client->search($params);
        return response()->json(['results' => $response['hits']['hits']]);
        //return response()->json($sort);

    }
}
