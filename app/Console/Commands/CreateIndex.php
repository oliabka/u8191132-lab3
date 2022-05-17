<?php

namespace App\Console\Commands;

use Elastic\Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;

class CreateIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates an ElasticSearch Index';

    /**
     * Execute the console command.
     *
     * @mixin ClientBuilder
     * @throws \Elastic\Elasticsearch\Exception\AuthenticationException
     */
    public function handle()
    {

        $hosts = [
            'http://127.0.0.1:9200',
        ];
        $client = ClientBuilder::create()->setHosts($hosts)->build();

        $params = [
            'index' => 'items_index',
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0,
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'name' => [
                            'type' => 'keyword'
                        ],
                        'description' => [
                            'type' => 'text'
                        ],
                        'amount' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ]
        ];
        $response = $client->indices()->create($params);
        dump($response);

    }
}
