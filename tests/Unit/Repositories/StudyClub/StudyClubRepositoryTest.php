<?php

namespace Tests\Unit\Repositories\StudyClub;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use App\Repositories\Contracts\StudyClubRepositoryInterface;
use App\Repositories\Eloquent\StudyClubRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyClubRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private StudyClubRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new StudyClubRepository();
    }

    /** @test */
    public function it_can_find_edition_by_id(): void
    {
        $edition = StudyClubEdition::factory()->create();
        StudyClubItem::factory()->forEdition($edition)->count(3)->create();

        $found = $this->repository->findEditionById($edition->id);

        $this->assertInstanceOf(StudyClubEdition::class, $found);
        $this->assertEquals($edition->id, $found->id);
        $this->assertCount(3, $found->items);
    }

    /** @test */
    public function it_returns_null_when_edition_not_found(): void
    {
        $found = $this->repository->findEditionById(99999);

        $this->assertNull($found);
    }

    /** @test */
    public function it_can_find_edition_by_number(): void
    {
        StudyClubEdition::factory()->create(['number' => 5]);
        StudyClubEdition::factory()->create(['number' => 10]);

        $found = $this->repository->findEditionByNumber(10);

        $this->assertInstanceOf(StudyClubEdition::class, $found);
        $this->assertEquals(10, $found->number);
    }

    /** @test */
    public function it_finds_all_editions_ordered_by_number(): void
    {
        StudyClubEdition::factory()->create(['number' => 3]);
        StudyClubEdition::factory()->create(['number' => 1]);
        StudyClubEdition::factory()->create(['number' => 2]);

        $editions = $this->repository->findAllEditions();

        $this->assertCount(3, $editions);
        $this->assertEquals(3, $editions->first()->number);
        $this->assertEquals(1, $editions->last()->number);
    }

    /** @test */
    public function it_finds_only_published_editions(): void
    {
        StudyClubEdition::factory()->published()->create(['number' => 1]);
        StudyClubEdition::factory()->inactive()->create(['number' => 2]);
        StudyClubEdition::factory()->future()->create(['number' => 3]);

        $published = $this->repository->findPublishedEditions();

        $this->assertCount(1, $published);
        $this->assertEquals(1, $published->first()->number);
    }

    /** @test */
    public function it_can_save_new_edition(): void
    {
        $edition = new StudyClubEdition([
            'number' => 42,
            'title' => 'Test Edition',
            'description' => 'Test Description',
            'publish_date' => now(),
            'status' => true,
        ]);

        $result = $this->repository->saveEdition($edition);

        $this->assertTrue($result);
        $this->assertDatabaseHas('studyclub_editions', [
            'number' => 42,
            'title' => 'Test Edition',
        ]);
    }

    /** @test */
    public function it_can_update_existing_edition(): void
    {
        $edition = StudyClubEdition::factory()->create(['title' => 'Old Title']);
        $edition->title = 'New Title';

        $result = $this->repository->saveEdition($edition);

        $this->assertTrue($result);
        $this->assertDatabaseHas('studyclub_editions', [
            'id' => $edition->id,
            'title' => 'New Title',
        ]);
    }

    /** @test */
    public function it_can_delete_edition(): void
    {
        $edition = StudyClubEdition::factory()->create();

        $result = $this->repository->deleteEdition($edition);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('studyclub_editions', [
            'id' => $edition->id,
        ]);
    }

    /** @test */
    public function it_can_find_item_by_id(): void
    {
        $edition = StudyClubEdition::factory()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create();

        $found = $this->repository->findItemById($item->id);

        $this->assertInstanceOf(StudyClubItem::class, $found);
        $this->assertEquals($item->id, $found->id);
        $this->assertInstanceOf(StudyClubEdition::class, $found->edition);
    }

    /** @test */
    public function it_can_find_items_by_edition(): void
    {
        $edition1 = StudyClubEdition::factory()->create();
        $edition2 = StudyClubEdition::factory()->create();
        StudyClubItem::factory()->forEdition($edition1)->count(3)->create();
        StudyClubItem::factory()->forEdition($edition2)->count(2)->create();

        $items = $this->repository->findItemsByEdition($edition1->id);

        $this->assertCount(3, $items);
        $items->each(function ($item) use ($edition1) {
            $this->assertEquals($edition1->id, $item->edition_id);
        });
    }

    /** @test */
    public function it_can_save_item(): void
    {
        $edition = StudyClubEdition::factory()->create();
        $item = new StudyClubItem([
            'edition_id' => $edition->id,
            'category' => 'TEST',
            'type' => 'article',
            'type_label' => 'Test Label',
            'author' => 'Test Author',
            'title' => 'Test Title',
            'resumo' => 'Test Resumo',
            'achados' => 'Test Achados',
            'implicacoes' => 'Test Implicacoes',
            'external_url' => 'https://test.com',
        ]);

        $result = $this->repository->saveItem($item);

        $this->assertTrue($result);
        $this->assertDatabaseHas('studyclub_items', [
            'title' => 'Test Title',
            'author' => 'Test Author',
        ]);
    }

    /** @test */
    public function it_can_delete_item(): void
    {
        $edition = StudyClubEdition::factory()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create();

        $result = $this->repository->deleteItem($item);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('studyclub_items', [
            'id' => $item->id,
        ]);
    }

    /** @test */
    public function it_can_find_item_by_edition_and_id(): void
    {
        $edition = StudyClubEdition::factory()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create();
        StudyClubItem::factory()->create(); // Outro item em outra edição

        $found = $this->repository->findItemByEditionAndId($edition->id, $item->id);

        $this->assertInstanceOf(StudyClubItem::class, $found);
        $this->assertEquals($item->id, $found->id);
        $this->assertEquals($edition->id, $found->edition_id);
    }

    /** @test */
    public function it_returns_null_when_item_not_in_edition(): void
    {
        $edition1 = StudyClubEdition::factory()->create();
        $edition2 = StudyClubEdition::factory()->create();
        $item = StudyClubItem::factory()->forEdition($edition2)->create();

        $found = $this->repository->findItemByEditionAndId($edition1->id, $item->id);

        $this->assertNull($found);
    }
}
