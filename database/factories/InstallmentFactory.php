<?php

namespace Database\Factories;

use App\Models\Installment;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Installment>
 */
class InstallmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Installment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // To make this factory self-sufficient, we'll create a tenant
        // if one isn't provided when calling the factory.
        $tenant = Tenant::factory()->create();

        return [
            'buyer_id' => $tenant->id,
            'unit_id' => $tenant->unit, // Get the unit ID from the tenant record
            'installment_number' => $this->faker->numberBetween(1, 48),
            'due_date' => $this->faker->dateTimeBetween('+1 month', '+4 years'),
            'amount' => $this->faker->randomFloat(2, 500, 2500),
            'status' => $this->faker->randomElement(['pending', 'paid']),
        ];
    }
}
