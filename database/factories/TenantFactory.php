<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Installment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // First, create a User with the 'tenant' type.
        $user = User::factory()->create(['type' => 'tenant']);

        // Create a property and a unit to associate with the tenant.
        $property = Property::factory()->create();
        $unit = PropertyUnit::factory()->create(['property_id' => $property->id]);

        return [
            'user_id' => $user->id,
            'family_member' => $this->faker->numberBetween(1, 5),
            'address' => $this->faker->streetAddress,
            'country' => $this->faker->country,
            'state' => $this->faker->state,
            'city' => $this->faker->city,
            'zip_code' => $this->faker->postcode,
            'property' => $property->id,
            'unit' => $unit->id,
            'purchase_type' => $this->faker->randomElement(['full', 'installment']),
            'email' => $user->email,
            'phone' => $user->phone_number,
            'profile_image' => $user->profile,
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Tenant $tenant) {
            // If the purchase type is 'installment', create a set of installments for this tenant.
            if ($tenant->purchase_type === 'installment') {
                $startDate = Carbon::now();
                $numberOfInstallments = $this->faker->numberBetween(12, 48); // e.g., 1 to 4 years of payments

                for ($i = 1; $i <= $numberOfInstallments; $i++) {
                    // FIX: Create the Installment model directly, removing the non-existent 'unit_id'.
                    Installment::create([
                        'buyer_id' => $tenant->id,
                        'installment_number' => $i,
                        'due_date' => $startDate->copy()->addMonths($i),
                        'status' => 'pending', // New installments are always pending
                        'amount' => $this->faker->randomFloat(2, 500, 2500),
                    ]);
                }
            }
        });
    }
}
