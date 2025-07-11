<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company() . ' Tower',
            'description' => $this->faker->paragraph,
            'type' => $this->faker->randomElement(['Residential', 'Commercial']),
            'country' => $this->faker->country,
            'state' => $this->faker->state,
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'address' => $this->faker->streetAddress,
            'parent_id' => 1, // Or use a User factory to get a real ID
            'is_active' => 1,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Property $property) {
            // After a property is created, automatically generate images for it.

            // Create one thumbnail image.
            PropertyImage::factory()->create([
                'property_id' => $property->id,
                'type' => 'thumbnail',
            ]);

            // Create 3 extra images.
            PropertyImage::factory()->count(3)->create([
                'property_id' => $property->id,
                'type' => 'extra',
            ]);
        });
    }
}
