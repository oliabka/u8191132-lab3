<?php

namespace App\Domain\Items\Actions;

use App\Domain\Items\Models\Item;
use Symfony\Component\Console\Input\Input;

class GetItemAction
{
    public function execute(int $id): Item
    {
        return Item::findOrFail($id);
    }
}
