<?php

namespace Tests\Feature;
use Illuminate\Testing\Fluent\AssertableJson;

it('gets an item by id', function(){

    $response = $this->getJson('/v1/items/1');
    $response->assertStatus(200)
    ->assertJson(fn (AssertableJson $json) =>
        $json->has('data', fn ($json) =>
            $json->where('id', 1)
            ->where('name', 'quis')
            ->where('description', 'Facere odio qui ipsam error.' )
            ->where('amount', 851)
    ));
});

it('posts an item', function()
{
    $response = $this->postJson('/v1/items', ['name'=>'test name', 'description'=>'test description', 'amount'=>100]);
    $response->assertStatus(201)
        ->assertJson(fn (AssertableJson $json) =>
        $json->has('data', fn ($json) =>
            $json->whereType('id', 'integer')
                ->where('name', 'test name')
                ->where('description', 'test description' )
                ->where('amount', 100)
    ));
});
