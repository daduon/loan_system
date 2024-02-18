<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_type_id' => $this->faker->unique()->randomElement([1,2,3]),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
            'customer_gender' => $this->faker->randomElement(['male', 'female']),
            'customer_dob' => $this->faker->date('Y-m-d', 'now'),
            'customer_address' => $this->faker->address(),
            'customer_phone' => $this->faker->phoneNumber(),
            'customer_identity' => $this->faker->creditCardNumber(),
            'customer_created_by' => 1,
            'customer_updated_by' => 1,
        ];
    }
}
