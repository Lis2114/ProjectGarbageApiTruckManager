<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Truck>
 */
class TruckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numberRegistration' => $this->faker->text(5) . "-" . strval($this->faker->numberBetween(100, 999)),
            'capacity' => $this->faker->numberBetween(100, 300)
        ];
    }
}
