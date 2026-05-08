<?php

namespace Tests\Feature\Controllers\StudyClub\Public;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyClubControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_studyclub_index(): void
    {
        StudyClubEdition::factory()->published()->count(3)->create();

        $response = $this->get('/studyclub');

        $response->assertOk();
        $response->assertViewIs('studyclub.index');
        $response->assertViewHas('editions');
    }

    /** @test */
    public function it_lists_editions_in_descending_order(): void
    {
        StudyClubEdition::factory()->published()->create(['number' => 5]);
        StudyClubEdition::factory()->published()->create(['number' => 10]);
        StudyClubEdition::factory()->published()->create(['number' => 3]);

        $response = $this->get('/studyclub');

        $editions = $response->viewData('editions');
        $this->assertEquals(10, $editions->first()->number);
        $this->assertEquals(3, $editions->last()->number);
    }

    /** @test */
    public function it_shows_edition_detail(): void
    {
        $edition = StudyClubEdition::factory()->published()->create(['number' => 9]);
        StudyClubItem::factory()->forEdition($edition)->count(2)->create();

        $response = $this->get("/studyclub/edition/{$edition->number}");

        $response->assertOk();
        $response->assertViewIs('studyclub.edition');
        $response->assertViewHas('edition');
    }

    /** @test */
    public function it_returns_404_for_nonexistent_edition(): void
    {
        $response = $this->get('/studyclub/edition/999');

        $response->assertNotFound();
    }

    /** @test */
    public function it_shows_item_detail(): void
    {
        $edition = StudyClubEdition::factory()->published()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create();

        $response = $this->get("/studyclub/{$edition->number}/{$item->id}");

        $response->assertOk();
        $response->assertViewIs('studyclub.show');
        $response->assertViewHas('edition');
        $response->assertViewHas('item');
    }

    /** @test */
    public function it_shows_related_articles_in_item_view(): void
    {
        $edition = StudyClubEdition::factory()->published()->create();
        $currentItem = StudyClubItem::factory()->forEdition($edition)->create();
        StudyClubItem::factory()->forEdition($edition)->count(3)->create();

        $response = $this->get("/studyclub/{$edition->number}/{$currentItem->id}");

        $response->assertViewHas('relatedArticles');
        $related = $response->viewData('relatedArticles');
        $this->assertCount(3, $related);
        $this->assertFalse($related->contains('id', $currentItem->id));
    }

    /** @test */
    public function it_returns_404_for_item_in_wrong_edition(): void
    {
        $edition1 = StudyClubEdition::factory()->published()->create(['number' => 1]);
        $edition2 = StudyClubEdition::factory()->published()->create(['number' => 2]);
        $item = StudyClubItem::factory()->forEdition($edition2)->create();

        $response = $this->get("/studyclub/{$edition1->number}/{$item->id}");

        $response->assertNotFound();
    }

    /** @test */
    public function it_displays_item_properties_correctly(): void
    {
        $edition = StudyClubEdition::factory()->published()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create([
            'title' => 'Test Article Title',
            'category' => 'ORTODONTIA',
            'author' => 'Dr. Test Silva',
            'type_label' => 'Artigo Original',
        ]);

        $response = $this->get("/studyclub/{$edition->number}/{$item->id}");

        $response->assertOk();
        $response->assertSee('Test Article Title');
        $response->assertSee('ORTODONTIA');
        $response->assertSee('Dr. Test Silva');
        $response->assertSee('Artigo Original');
    }

    /** @test */
    public function it_generates_correct_image_url(): void
    {
        $edition = StudyClubEdition::factory()->published()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create([
            'image_path' => 'studyclub/test-image.jpg',
        ]);

        $response = $this->get("/studyclub/{$edition->number}/{$item->id}");

        $responseItem = $response->viewData('item');
        $this->assertStringContainsString('storage/studyclub/test-image.jpg', $responseItem->image_url);
    }

    /** @test */
    public function it_handles_legacy_image_paths(): void
    {
        $edition = StudyClubEdition::factory()->published()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create([
            'image_path' => 'imagens/fotos_study/legacy.jpg',
        ]);

        $response = $this->get("/studyclub/{$edition->number}/{$item->id}");

        $responseItem = $response->viewData('item');
        // Deve retornar o path completo usando asset()
        $this->assertNotEmpty($responseItem->image_url);
    }

    /** @test */
    public function it_shows_external_url_link(): void
    {
        $edition = StudyClubEdition::factory()->published()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create([
            'external_url' => 'https://dentalgo.com.br/artigo/123',
        ]);

        $response = $this->get("/studyclub/{$edition->number}/{$item->id}");

        $response->assertSee('https://dentalgo.com.br/artigo/123');
    }

    /** @test */
    public function it_shows_formatted_publish_date(): void
    {
        $edition = StudyClubEdition::factory()->published()->create([
            'publish_date' => '2026-05-06',
        ]);

        $response = $this->get('/studyclub');

        $response->assertSee('06/05/2026');
    }

    /** @test */
    public function it_filters_inactive_editions_from_public_list(): void
    {
        StudyClubEdition::factory()->published()->create(['number' => 1, 'status' => true]);
        StudyClubEdition::factory()->create(['number' => 2, 'status' => false, 'publish_date' => now()->subDay()]);

        $response = $this->get('/studyclub');

        $editions = $response->viewData('editions');
        $this->assertCount(1, $editions);
        $this->assertEquals(1, $editions->first()->number);
    }

    /** @test */
    public function it_filters_future_editions_from_public_list(): void
    {
        StudyClubEdition::factory()->published()->create(['number' => 1]);
        StudyClubEdition::factory()->create(['number' => 2, 'publish_date' => now()->addMonth(), 'status' => true]);

        $response = $this->get('/studyclub');

        $editions = $response->viewData('editions');
        $this->assertCount(1, $editions);
        $this->assertEquals(1, $editions->first()->number);
    }
}
