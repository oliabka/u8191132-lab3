<?php

namespace App\Domain\Items\Actions;
use App\Domain\Items\Models\Item;
class DeleteItemAction
{
    public function execute(int $id): Item
    {
        return Item::findOrFail($id)->delete();
    }
}
