<?php

namespace Database\Factories;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proyek>
 */
class ProyekFactory extends Factory
{
    protected $model = Proyek::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->client(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraphs(3, true),
            'budget' => fake()->numberBetween(10000000, 150000000),
            'category' => fake()->randomElement([
                'Residensial (Rumah, Villa, Apartemen)',
                'Komersial (Ruko, Kantor, Hotel, Kafe)',
                'Desain Interior',
                'Lansekap & Taman',
                'Urban Planning & Kawasan',
                'Renovasi & Sipil',
                'Lainnya',
            ]),
            'location' => fake()->city(),
            'attachment_path' => null,
            'status' => 'aktif',
        ];
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'ditutup',
        ]);
    }
}
