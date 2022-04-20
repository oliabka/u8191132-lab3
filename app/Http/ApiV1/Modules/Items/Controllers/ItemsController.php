<?php

namespace App\Http\ApiV1\Modules\Items\Controllers;
use App\Domain\Items\Actions\PostItemAction;
use App\Domain\Items\Actions\DeleteItemAction;
use App\Domain\Items\Actions\PatchItemAction;
use App\Domain\Items\Actions\PutItemAction;
use App\Domain\Items\Actions\GetItemAction;
use App\Http\ApiV1\Modules\Items\Requests\PostPutItemRequest;
use App\Http\ApiV1\Modules\Items\Requests\PatchItemRequest;
use App\Http\ApiV1\Modules\Items\Resources\ItemsResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class ItemsController extends Controller
{
    public function post(PostPutItemRequest $request,
                           PostItemAction $action
    ) {
        return new ItemsResource($action->execute($request->validated()));
    }
    public function patch(int $id,
                          PatchItemRequest $request,
                          PatchItemAction $action
    ) {
        return new ItemsResource(
            $action->execute($id, $request->validated())
        );
    }
    public function put(int $id,
                          PostPutItemRequest $request,
                          PutItemAction $action
    ) {
        return new ItemsResource(
            $action->execute($id, $request->validated())
        );
    }
    public function delete(int $id, DeleteItemAction $action)
    {
        $action->execute($id);
        return json_encode(array('data'=>null));
    }
    public function get(int $id, GetItemAction $action)
    {
        return new ItemsResource($action->execute($id));
    }
}
