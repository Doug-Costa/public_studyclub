<?php

namespace Database\Factories;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudyClubItem>
 */
class StudyClubItemFactory extends Factory
{
    protected $model = StudyClubItem::class;

    public function definition(): array
    {
        $categories = ['ORTODONTIA', 'ENDODONTIA', 'ODONTOLOGIA DO SONO', 'ENTREVISTA', 'INTELIGÊNCIA ARTIFICIAL'];
        $types = ['article', 'interview', 'special'];
        $icons = ['bi-journal-text', 'bi-mic', 'bi-star-fill', 'bi-robot', 'bi-lightbulb'];

        return [
            'edition_id' => StudyClubEdition::factory(),
            'category' => $this->faker->randomElement($categories),
            'type' => $this->faker->randomElement($types),
            'type_label' => $this->faker->words(2, true),
            'author' => $this->faker->name(),
            'title' => $this->faker->sentence(),
            'resumo' => $this->faker->paragraph(3),
            'achados' => $this->faker->paragraph(2),
            'implicacoes' => $this->faker->paragraph(2),
            'image_path' => 'studyclub/' . $this->faker->uuid() . '.jpg',
            'external_url' => $this->faker->url(),
            'likes' => $this->faker->numberBetween(0, 1000),
            'comments' => $this->faker->numberBetween(0, 100),
            'icon' => $this->faker->randomElement($icons),
        ];
    }

    public function article(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'article',
            'type_label' => 'Artigo Original',
            'icon' => 'bi-journal-text',
        ]);
    }

    public function interview(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'interview',
            'type_label' => 'Entrevista',
            'icon' => 'bi-mic',
        ]);
    }

    public function forEdition(StudyClubEdition $edition): static
    {
        return $this->state(fn (array $attributes) => [
            'edition_id' => $edition->id,
        ]);
    }
}
