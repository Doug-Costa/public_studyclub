<?php

namespace Tests\Unit\StudyClub;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudyClubEditionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_edition(): void
    {
        $edition = StudyClubEdition::factory()->create([
            'number' => 10,
            'title' => 'Study Club #10',
        ]);

        $this->assertDatabaseHas('studyclub_editions', [
            'number' => 10,
            'title' => 'Study Club #10',
        ]);

        $this->assertInstanceOf(StudyClubEdition::class, $edition);
    }

    /** @test */
    public function it_has_many_items(): void
    {
        $edition = StudyClubEdition::factory()->create();
        $item1 = StudyClubItem::factory()->forEdition($edition)->create();
        $item2 = StudyClubItem::factory()->forEdition($edition)->create();

        $this->assertCount(2, $edition->items);
        $this->assertTrue($edition->items->contains($item1));
        $this->assertTrue($edition->items->contains($item2));
    }

    /** @test */
    public function it_casts_publish_date_to_date(): void
    {
        $edition = StudyClubEdition::factory()->create([
            'publish_date' => '2026-05-06',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $edition->publish_date);
    }

    /** @test */
    public function it_casts_number_to_integer(): void
    {
        $edition = StudyClubEdition::factory()->create([
            'number' => '42',
        ]);

        $this->assertIsInt($edition->number);
        $this->assertEquals(42, $edition->number);
    }

    /** @test */
    public function it_casts_status_to_boolean(): void
    {
        $edition = StudyClubEdition::factory()->create([
            'status' => 1,
        ]);

        $this->assertIsBool($edition->status);
        $this->assertTrue($edition->status);
    }

    /** @test */
    public function scope_active_returns_only_active_editions(): void
    {
        StudyClubEdition::factory()->create(['status' => true, 'number' => 1]);
        StudyClubEdition::factory()->inactive()->create(['number' => 2]);
        StudyClubEdition::factory()->create(['status' => true, 'number' => 3]);

        $activeEditions = StudyClubEdition::active()->get();

        $this->assertCount(2, $activeEditions);
        $this->assertTrue($activeEditions->every(fn ($e) => $e->status === true));
    }

    /** @test */
    public function scope_latest_editions_orders_by_number_desc(): void
    {
        StudyClubEdition::factory()->create(['number' => 5]);
        StudyClubEdition::factory()->create(['number' => 10]);
        StudyClubEdition::factory()->create(['number' => 3]);

        $editions = StudyClubEdition::latestEditions()->get();

        $this->assertEquals(10, $editions->first()->number);
        $this->assertEquals(3, $editions->last()->number);
    }

    /** @test */
    public function scope_published_returns_only_past_dates(): void
    {
        StudyClubEdition::factory()->published()->create(['number' => 1]);
        StudyClubEdition::factory()->future()->create(['number' => 2]);

        $published = StudyClubEdition::published()->get();

        $this->assertCount(1, $published);
        $this->assertEquals(1, $published->first()->number);
    }

    /** @test */
    public function it_returns_formatted_date_attribute(): void
    {
        $edition = StudyClubEdition::factory()->create([
            'publish_date' => '2026-05-06',
        ]);

        $this->assertEquals('06/05/2026', $edition->formatted_date);
    }

    /** @test */
    public function is_published_returns_true_when_active_and_past_date(): void
    {
        $edition = StudyClubEdition::factory()->published()->create();

        $this->assertTrue($edition->isPublished());
    }

    /** @test */
    public function is_published_returns_false_when_inactive(): void
    {
        $edition = StudyClubEdition::factory()->inactive()->create([
            'publish_date' => now()->subDay(),
        ]);

        $this->assertFalse($edition->isPublished());
    }

    /** @test */
    public function is_published_returns_false_when_future_date(): void
    {
        $edition = StudyClubEdition::factory()->future()->create();

        $this->assertFalse($edition->isPublished());
    }

    /** @test */
    public function number_must_be_unique(): void
    {
        StudyClubEdition::factory()->create(['number' => 1]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        StudyClubEdition::factory()->create(['number' => 1]);
    }
}
