<?php

namespace App\Domain\Items\Actions;

use App\Domain\Items\Models\Item;

class DeleteItemAction
{
    public function execute(int $id): void
    {
        Item::findOrFail($id)->delete();
    }
}
