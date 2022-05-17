<?php

namespace App\Domain\Items\Actions;

use App\Domain\Items\Models\Item;

class PutItemAction
{
    public function execute(int $id, array $fields): Item
    {
        $item = Item::findOrFail($id);
        $item->name = $fields['name'];
        $item->description = array_key_exists('description', $fields) ? $fields['description'] : 'no description';
        $item->amount = array_key_exists('amount', $fields) ? $fields['amount'] : 0;
        $item->save();
        return $item;
    }
}
