<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
          return [
            'name' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'created_at' => $this->faker->dateTimeBetween('-2 year'),
            'updated_at' => $this->faker->dateTimeBetween('created_at', 'now'),
        ];
    }
}
