<?php

namespace Database\Factories;

use App\Models\PropertyImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyImage>
 */
class PropertyImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PropertyImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // The property_id will be provided when this factory is called.
            // This will generate a random placeholder image URL.
            'image' => $this->faker->imageUrl(1024, 768, 'apartments', true),
            'type' => 'extra', // Default type is 'extra'
        ];
    }
}
