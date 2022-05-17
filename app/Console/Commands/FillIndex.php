<?php

namespace App\Console\Commands;

use App\Domain\Items\IndexActions\AddDocumentIndexAction;
use App\Domain\Items\Models\Item;
use Illuminate\Console\Command;

class FillIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:fill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fills an ElasticSearch Index with data from a table';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(AddDocumentIndexAction $action): void
    {
        $items = Item::all();
        foreach ($items as &$item) {
            $action->execute($item);
        }
    }
}
