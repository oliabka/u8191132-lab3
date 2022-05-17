<?php

namespace App\Http\ApiV1\Modules\Items\Tests;

use App\Domain\Items\Models\Item;
use App\Domain\Shipments\Models\Shipment;
use Faker\Provider\DateTime;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\patchJson;
use Tests\TestCase;


uses(TestCase::class, DatabaseTransactions::class);

/**
 * Tests for the GET method
 */
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

it('gets an item by id with shipments included', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var Shipment $shipment */
    $shipment = Shipment::factory()->create(['item_id' => $item->id]);
    $response = getJson("/v1/items/{$item->id}?include=shipments");
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $item->name)
            ->where('description', $item->description)
            ->where('amount', $item->amount)
            ->has('shipments.0', fn($json) => $json->where('id', $shipment->id)
                ->where('supplier', $shipment->supplier)
                ->where('amount', $shipment->amount)
                ->where('item_id', $item->id)
                ->has('date_time')
            )));
});

/**
 * Tests for the POST method
 */
it('posts a whole item', function () {
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

it('posts an item with default optional data (name only)', function () {
    /** @var array $item */
    $item = Item::factory()->raw();
    $response = postJson('/v1/items', ["name" => $item['name']]);
    $response->assertStatus(201)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->whereType('id', 'integer')
            ->where('name', $item['name'])
            ->where('description', 'no description')
            ->where('amount', 0)
        ));
    $this->assertDatabaseHas('items', ["name" => $item['name'], 'description' => 'no description', 'amount' => 0]);
});

it('posts an item with default optional data (name + description)', function () {
    /** @var array $item */
    $item = Item::factory()->raw();
    $response = postJson('/v1/items', ["name" => $item['name'], "description" => $item['description']]);
    $response->assertStatus(201)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->whereType('id', 'integer')
            ->where('name', $item['name'])
            ->where('description', $item['description'])
            ->where('amount', 0)
        ));
    $this->assertDatabaseHas('items', ["name" => $item['name'], 'description' => $item['description'], 'amount' => 0]);
});

it('posts an item with default optional data (name + amount)', function () {
    /** @var array $item */
    $item = Item::factory()->raw();
    $response = postJson('/v1/items', ["name" => $item['name'], "amount" => $item['amount']]);
    $response->assertStatus(201)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->whereType('id', 'integer')
            ->where('name', $item['name'])
            ->where('description', 'no description')
            ->where('amount', $item['amount'])
        ));
    $this->assertDatabaseHas('items', ["name" => $item['name'], 'description' => 'no description', 'amount' => $item['amount']]);
});

it('does not post an item without a name field', function () {
    $response = postJson('/v1/items', []);
    $response->assertStatus(422);
});

it('does not post an item without a name field(only optional parameters)', function () {
    /** @var array $item */
    $item = Item::factory()->raw();
    $response = postJson("/v1/items", ["description" => $item['description'], "amount" => $item['amount']]);
    $response->assertStatus(422);
});

it('does not post an item without a name field (only description)', function () {
    /** @var array $item */
    $item = Item::factory()->raw();
    $response = postJson("/v1/items", ["description" => $item['description']]);
    $response->assertStatus(422);
});

it('does not post an item without a name field (only amount)', function () {
    /** @var array $item */
    $item = Item::factory()->raw();
    $response = postJson("/v1/items", ["amount" => $item['amount']]);
    $response->assertStatus(422);
});

/**
 * Tests for the DELETE method
 */
it('can delete an item', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    $response = deleteJson("/v1/items/{$item->id}");
    $response->assertStatus(200)
        ->assertJson(["data" => null]);
    $this->assertDatabaseMissing('items', $item->attributesToArray());
});

/**
 * Tests for the PUT method
 */
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

it('can put an item with default optional data (name only)', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = putJson("/v1/items/{$item->id}", ["name" => $newItem['name']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', 'no description')
            ->where('amount', 0)));
    $this->assertDatabaseHas('items', ["name" => $newItem['name'], 'description' => 'no description', 'amount' => 0]);
});

it('can put an item with default optional data (name + description)', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = putJson("/v1/items/{$item->id}", ["name" => $newItem['name'], "description" => $newItem['description']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', $newItem['description'])
            ->where('amount', 0)));
    $this->assertDatabaseHas('items', ["name" => $newItem['name'], 'description' => $newItem['description'], 'amount' => 0]);
});

it('can put an item with default optional data (name + amount)', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = putJson("/v1/items/{$item->id}", ["name" => $newItem['name'], "amount" => $newItem['amount']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', 'no description')
            ->where('amount', $newItem['amount'])));
    $this->assertDatabaseHas('items', ["name" => $newItem['name'], 'description' => 'no description', 'amount' => $newItem['amount']]);
});

it('does not put an item without name (no parameters)', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    $response = putJson("/v1/items/{$item->id}", []);
    $response->assertStatus(422);
});

it('does not put an item without name (only optional parameters)', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = putJson("/v1/items/{$item->id}", ["description" => $newItem['description'], "amount" => $newItem['amount']]);
    $response->assertStatus(422);
});

it('does not put an item without name (only description)', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = putJson("/v1/items/{$item->id}", ["description" => $newItem['description']]);
    $response->assertStatus(422);
});

it('does not put an item without name (only amount)', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = putJson("/v1/items/{$item->id}", ["amount" => $newItem['amount']]);
    $response->assertStatus(422);
});

/**
 * Tests for the PATCH method
 */
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

it('can patch two fields (name + description) ', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = patchJson("/v1/items/{$item->id}", ["name" => $newItem['name'], "description" => $newItem['description']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', $newItem['description'])
            ->where('amount', $item->amount)
        ));
    $this->assertDatabaseHas('items', ["name" => $newItem['name'], "description" => $newItem['description'], "amount" => $item->amount]);
});

it('can patch two fields (name + amount) ', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = patchJson("/v1/items/{$item->id}", ["name" => $newItem['name'], "amount" => $newItem['amount']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', $item->description)
            ->where('amount', $newItem['amount'])
        ));
    $this->assertDatabaseHas('items', ["name" => $newItem['name'], "description" => $item->description, "amount" => $newItem['amount']]);
});

it('can patch two fields (description + amount) ', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = patchJson("/v1/items/{$item->id}", ["description" => $newItem['description'], "amount" => $newItem['amount']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $item->name)
            ->where('description', $newItem['description'])
            ->where('amount', $newItem['amount'])
        ));
    $this->assertDatabaseHas('items', ["name" => $item->name, "description" => $newItem['description'], "amount" => $newItem['amount']]);
});

it('can patch a single field (name) ', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = patchJson("/v1/items/{$item->id}", ["name" => $newItem['name']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $newItem['name'])
            ->where('description', $item->description)
            ->where('amount', $item->amount)
        ));
    $this->assertDatabaseHas('items', ["name" => $newItem['name'], "description" => $item->description, "amount" => $item->amount]);
});

it('can patch a single field (description) ', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = patchJson("/v1/items/{$item->id}", ["description" => $newItem['description']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $item->name)
            ->where('description', $newItem['description'])
            ->where('amount', $item->amount)
        ));
    $this->assertDatabaseHas('items', ["name" => $item->name, "description" => $newItem['description'], "amount" => $item->amount]);
});

it('can patch a single field (amount) ', function () {
    /** @var Item $item */
    $item = Item::factory()->create();
    /** @var array $newItem */
    $newItem = Item::factory()->raw();
    $response = patchJson("/v1/items/{$item->id}", ["amount" => $newItem['amount']]);
    $response->assertStatus(200)
        ->assertJson(fn(AssertableJson $json) => $json->has('data', fn($json) => $json->where('id', $item->id)
            ->where('name', $item->name)
            ->where('description', $item->description)
            ->where('amount', $newItem['amount'])
        ));
    $this->assertDatabaseHas('items', ["name" => $item->name, "description" => $item->description, "amount" => $newItem['amount']]);
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
