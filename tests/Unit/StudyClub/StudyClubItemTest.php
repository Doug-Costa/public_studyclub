<?php

namespace Tests\Unit\StudyClub;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyClubItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_item(): void
    {
        $edition = StudyClubEdition::factory()->create();

        $item = StudyClubItem::factory()->create([
            'edition_id' => $edition->id,
            'title' => 'Test Article',
            'category' => 'ORTODONTIA',
        ]);

        $this->assertDatabaseHas('studyclub_items', [
            'title' => 'Test Article',
            'category' => 'ORTODONTIA',
        ]);
    }

    /** @test */
    public function it_belongs_to_an_edition(): void
    {
        $edition = StudyClubEdition::factory()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create();

        $this->assertInstanceOf(StudyClubEdition::class, $item->edition);
        $this->assertEquals($edition->id, $item->edition->id);
    }

    /** @test */
    public function it_casts_likes_to_integer(): void
    {
        $item = StudyClubItem::factory()->create(['likes' => '100']);

        $this->assertIsInt($item->likes);
        $this->assertEquals(100, $item->likes);
    }

    /** @test */
    public function it_casts_comments_to_integer(): void
    {
        $item = StudyClubItem::factory()->create(['comments' => '50']);

        $this->assertIsInt($item->comments);
        $this->assertEquals(50, $item->comments);
    }

    /** @test */
    public function it_has_valid_types(): void
    {
        $this->assertContains('article', StudyClubItem::TYPES);
        $this->assertContains('interview', StudyClubItem::TYPES);
        $this->assertContains('special', StudyClubItem::TYPES);
    }

    /** @test */
    public function scope_of_type_filters_by_type(): void
    {
        $edition = StudyClubEdition::factory()->create();
        StudyClubItem::factory()->forEdition($edition)->article()->create();
        StudyClubItem::factory()->forEdition($edition)->interview()->create();

        $articles = StudyClubItem::ofType('article')->get();

        $this->assertCount(1, $articles);
        $this->assertEquals('article', $articles->first()->type);
    }

    /** @test */
    public function scope_by_category_filters_by_category(): void
    {
        $edition = StudyClubEdition::factory()->create();
        StudyClubItem::factory()->forEdition($edition)->create(['category' => 'ORTODONTIA']);
        StudyClubItem::factory()->forEdition($edition)->create(['category' => 'ENDODONTIA']);

        $ortoItems = StudyClubItem::byCategory('ORTODONTIA')->get();

        $this->assertCount(1, $ortoItems);
        $this->assertEquals('ORTODONTIA', $ortoItems->first()->category);
    }

    /** @test */
    public function it_returns_storage_url_for_image(): void
    {
        $item = StudyClubItem::factory()->create([
            'image_path' => 'studyclub/test-image.jpg',
        ]);

        $this->assertStringContainsString('storage/studyclub/test-image.jpg', $item->image_url);
    }

    /** @test */
    public function it_returns_default_image_when_path_is_null(): void
    {
        $item = StudyClubItem::factory()->create([
            'image_path' => null,
        ]);

        $this->assertStringContainsString('imagens/fotos_study/default.jpg', $item->image_url);
    }

    /** @test */
    public function it_returns_external_url_when_path_is_http(): void
    {
        $item = StudyClubItem::factory()->create([
            'image_path' => 'https://example.com/image.jpg',
        ]);

        $this->assertEquals('https://example.com/image.jpg', $item->image_url);
    }

    /** @test */
    public function it_returns_uppercase_category(): void
    {
        $item = StudyClubItem::factory()->create([
            'category' => 'ortodontia',
        ]);

        $this->assertEquals('ORTODONTIA', $item->formatted_category);
    }

    /** @test */
    public function is_article_returns_true_for_articles(): void
    {
        $article = StudyClubItem::factory()->article()->make();
        $interview = StudyClubItem::factory()->interview()->make();

        $this->assertTrue($article->isArticle());
        $this->assertFalse($interview->isArticle());
    }

    /** @test */
    public function is_interview_returns_true_for_interviews(): void
    {
        $article = StudyClubItem::factory()->article()->make();
        $interview = StudyClubItem::factory()->interview()->make();

        $this->assertFalse($article->isInterview());
        $this->assertTrue($interview->isInterview());
    }

    /** @test */
    public function it_increments_likes(): void
    {
        $item = StudyClubItem::factory()->create(['likes' => 10]);

        $item->incrementLikes();

        $this->assertEquals(11, $item->fresh()->likes);
    }

    /** @test */
    public function it_increments_comments(): void
    {
        $item = StudyClubItem::factory()->create(['comments' => 5]);

        $item->incrementComments();

        $this->assertEquals(6, $item->fresh()->comments);
    }

    /** @test */
    public function it_cascades_delete_when_edition_is_deleted(): void
    {
        $edition = StudyClubEdition::factory()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create();

        $edition->delete();

        $this->assertDatabaseMissing('studyclub_items', [
            'id' => $item->id,
        ]);
    }
}
