<?php

namespace App\Domain\Items\Actions;

use App\Domain\Items\Models\Item;

class PatchItemAction
{
    public function execute(int $id, array $fields): Item
    {
        $item = Item::findOrFail($id);
        $item->update($fields);
        return $item;
    }
}
