<?php

namespace App\Http\ApiV1\Modules\Items\Controllers;

use App\Domain\Items\Actions\PostItemAction;
use App\Domain\Items\Actions\DeleteItemAction;
use App\Domain\Items\Actions\PatchItemAction;
use App\Domain\Items\Actions\PutItemAction;
use App\Domain\Items\Actions\GetItemAction;
use App\Http\ApiV1\Modules\Items\Requests\PutItemRequest;
use App\Http\ApiV1\Modules\Items\Requests\PostItemRequest;
use App\Http\ApiV1\Modules\Items\Requests\PatchItemRequest;
use App\Http\ApiV1\Modules\Items\Resources\ItemsResource;
use App\Http\Controllers\Controller;
use \Illuminate\Http\JsonResponse;

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

    public function get(int $id, GetItemAction $action): ItemsResource
    {
        return new  ItemsResource($action->execute($id));
    }
}
