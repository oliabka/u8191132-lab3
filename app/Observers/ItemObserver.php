<?php

namespace App\Observers;

use App\Domain\Items\IndexActions\AddDocumentIndexAction;
use App\Domain\Items\IndexActions\DeleteDocumentIndexAction;
use App\Domain\Items\IndexActions\EditDocumentIndexAction;
use App\Domain\Items\Models\Item;

class ItemObserver
{
    /**
     * Handle the Item "created" event.
     *
     * @param Item $item
     * @return void
     */
    public function created(Item $item): void
    {
        $action = new AddDocumentIndexAction();
        $action->execute($item);
    }

    /**
     * Handle the Item "updated" event.
     *
     * @param Item $item
     * @return void
     */
    public function updated(Item $item): void
    {
        $action = new EditDocumentIndexAction();
        $action->execute($item);
    }

    /**
     * Handle the Item "deleted" event.
     *
     * @param Item $item
     * @return void
     */
    public function deleted(Item $item): void
    {
        $action = new DeleteDocumentIndexAction();
        $action->execute($item);
    }
}
