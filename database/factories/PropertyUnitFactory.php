<?php

namespace Database\Factories;

use App\Models\Property;
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
            'status' => 'Available',
            'parent_id' => 1, // Or use a User factory to get a real ID
            'property_id' => Property::factory(), // This will auto-create a property
        ];
    }

    /**
     * Create units for an existing property
     */
    public function forProperty($propertyId)
    {
        return $this->state(function (array $attributes) use ($propertyId) {
            return [
                'property_id' => $propertyId,
            ];
        });
    }

    /**
     * Create multiple units for the same property
     */
    public function sameProperty()
    {
        static $property = null;

        if (!$property) {
            $property = Property::factory()->create();
        }

        return $this->state(function (array $attributes) use ($property) {
            return [
                'property_id' => $property->id,
            ];
        });
    }
}
