<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subdomain>
 */
class SubdomainFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => Str::lower(Str::random()),
            'user_id' => 4,
            'expiration_date' => fake()->dateTimeBetween('+6 months', '+1 years'),
        ];
    }

}
