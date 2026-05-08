<?php

namespace Database\Factories;

use App\Models\StudyClubEdition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyClubEdition>
 */
class StudyClubEditionFactory extends Factory
{
    protected $model = StudyClubEdition::class;

    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->numberBetween(1, 100),
            'title' => 'Study Club #' . $this->faker->numberBetween(1, 100),
            'description' => $this->faker->paragraph(),
            'publish_date' => $this->faker->date(),
            'status' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'publish_date' => now()->subDays(5),
            'status' => true,
        ]);
    }

    public function future(): static
    {
        return $this->state(fn (array $attributes) => [
            'publish_date' => now()->addDays(5),
            'status' => true,
        ]);
    }
}
