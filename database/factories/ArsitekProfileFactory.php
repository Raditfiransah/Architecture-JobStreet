<?php

namespace Database\Factories;

use App\Models\ArsitekProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ArsitekProfile>
 */
class ArsitekProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'status_pekerjaan' => fake()->randomElement(['Available', 'Hired', 'Freelance']),
            'is_student' => fake()->boolean(),
            'location' => fake()->city() . ', Indonesia',
            // 'school' => fake()->company() . ' University',
            'degree_type' => fake()->randomElement(['S1 Arsitektur', 'S2 Arsitektur', 'D3 Teknik Sipil']),
            'preferences' => [fake()->jobTitle(), fake()->jobTitle()],
            'resume_url' => fake()->url(),
            'portfolio_url' => fake()->url(),
        ];
    }
}
