<?php

namespace Database\Factories;

use App\Models\PropertyUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyUnit>
 */
class PropertyUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PropertyUnit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'Unit ' . $this->faker->bothify('##??'), // e.g., Unit 42AB
            'bedroom' => $this->faker->numberBetween(1, 4),
            'baths' => $this->faker->numberBetween(1, 3),
            'kitchen' => $this->faker->numberBetween(1, 2),
            'status' => 'available',
            'parent_id' => 1, // Or use a User factory to get a real ID
            // 'property_id' is intentionally left out, as it should be provided
            // when calling the factory, e.g., PropertyUnit::factory()->create(['property_id' => 5])
        ];
    }
}
