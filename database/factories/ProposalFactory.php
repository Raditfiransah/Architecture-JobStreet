<?php

namespace Database\Factories;

use App\Models\Proposal;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proposal>
 */
class ProposalFactory extends Factory
{
    protected $model = Proposal::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->arsitek(),
            'proyek_id' => Proyek::factory(),
            'bid_amount' => fake()->numberBetween(5000000, 100000000),
            'estimated_time' => fake()->numberBetween(7, 90),
            'description' => fake()->paragraphs(3, true),
            'attachment_path' => null,
            'status' => 'pending',
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'diterima',
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => 'ditolak',
        ]);
    }
}
