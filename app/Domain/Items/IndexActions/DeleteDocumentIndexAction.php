<?php

namespace App\Domain\Items\IndexActions;

use App\Domain\Items\Models\Item;
use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;

class DeleteDocumentIndexAction
{
    /**
     */
    public function execute(Item $item): void
    {
        $hosts = [
            'http://127.0.0.1:9200',
        ];
        
        try {
            $client = ClientBuilder::create()->setHosts($hosts)->build();
            $response = $client->delete([
                'index' => 'items_index',
                'id' => $item->id
            ]);
        } catch (AuthenticationException|ClientResponseException|MissingParameterException|ServerResponseException $e) {
        }
    }
}
