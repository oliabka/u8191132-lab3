<?php

namespace App\Http\ApiV1\Modules\Items\Tests;

use App\Domain\Items\Models\Item;
use Illuminate\Testing\Fluent\AssertableJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\patchJson;
use Tests\TestCase;


uses(TestCase::class);

it('gets an item by id', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    $response = getJson("/v1/items/{$item->id}");
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $item->name)
            ->where('description', $item->description)
            ->where('amount', $item->amount)
        ));
});

it('posts an item', function () {
    /** @var array $item */
    $item = Item::factory()->raw();
    $response = postJson('/v1/items', $item);
    $response->assertStatus(201)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->whereType('id', 'integer')
            ->where('name', $item['name'])
            ->where('description', $item['description'])
            ->where('amount', $item['amount'])
        ));
    $this->assertDatabaseHas('items', $item);
});

it('does not post an item without a name field', function () {
    $response = postJson('/v1/items', []);
    $response->assertStatus(422);
});

it('can delete an item', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    $response = deleteJson("/v1/items/{$item->id}");
    $response->assertStatus(200)
        ->assertJson(["data" => null]);
    $this->assertDatabaseMissing('items', $item->attributesToArray());
});

it('can put a whole item', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = putJson("/v1/items/{$item->id}", $newItem);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', $newItem['description'])
            ->where('amount', $newItem['amount'])));
    $this->assertDatabaseHas('items', $newItem);
});

it('can put with only a name', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = putJson("/v1/items/{$item->id}", array("name" => $newItem['name']));
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', 'no description')
            ->where('amount', 0)));
    $this->assertDatabaseHas('items', ["name" => $newItem['name'], 'description' => 'no description', 'amount' => 0]);
});

it('fails put without name', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    $response = putJson("/v1/items/{$item->id}", []);
    $response->assertStatus(422);
});

it('can patch a whole item', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = patchJson("/v1/items/{$item->id}", $newItem);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', $newItem['description'])
            ->where('amount', $newItem['amount'])));
    $this->assertDatabaseHas('items', $newItem);
});

it('can patch a single field ', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = patchJson("/v1/items/{$item->id}", array("description" => $newItem['description']));
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $item->name)
            ->where('description', $newItem['description'])
            ->where('amount', $item->amount)
        ));
    $this->assertDatabaseHas('items', ["name" => $item->name, "description" => $newItem['description'], "amount" => $item->amount]);
});

it('can patch with no changes', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    $response = patchJson("/v1/items/{$item->id}", []);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->whereType('id', 'integer')
            ->where('name', $item['name'])
            ->where('description', $item['description'])
            ->where('amount', $item['amount'])
        ));
    $this->assertDatabaseHas('items', $item->attributesToArray());
});
