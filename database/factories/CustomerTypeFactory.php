<?php

namespace Database\Factories;

use App\Models\CustomerType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerType>
 */
class CustomerTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_type_name' => $this->faker->unique()->randomElement(['VIP', 'General', 'Special']),
            'customer_type_des' => $this->faker->text(20),
            'customer_type_rate' => $this->faker->unique()->randomElement(['10', '20', '30']),
            'customer_type_created_by' => 1,
            'customer_type_updated_by' => 1,
        ];
    }
}
