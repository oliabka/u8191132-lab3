<?php

namespace App\Domain\Shipments\Models\Factories;

use App\Domain\Items\Models\Item;
use App\Domain\Shipments\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'supplier' => $this->faker->unique()->company(),
            'amount' => $this->faker->numberBetween(0, 200),
            'date_time' => $this->faker->dateTime(now(), null),
            'item_id' => Item::all()->random()->id,
        ];
    }
}
