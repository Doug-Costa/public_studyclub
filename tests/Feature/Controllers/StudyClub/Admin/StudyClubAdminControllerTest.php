<?php

namespace Tests\Feature\Controllers\StudyClub\Admin;

use App\Models\StudyClubEdition;
use App\Models\StudyClubItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudyClubAdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function actingAsAdmin(): User
    {
        $user = User::factory()->create();
        // Simula autenticação (adapte conforme seu sistema de roles)
        return $user;
    }

    /** @test */
    public function unauthenticated_users_cannot_access_admin(): void
    {
        $response = $this->get('/admin_studyclub');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_users_can_see_dashboard(): void
    {
        $user = $this->actingAsAdmin();
        StudyClubEdition::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin_studyclub');

        $response->assertOk();
        $response->assertViewIs('admin.studyclub.index');
        $response->assertViewHas('editions');
    }

    /** @test */
    public function it_shows_create_form(): void
    {
        $user = $this->actingAsAdmin();

        $response = $this->actingAs($user)->get('/admin_studyclub/create');

        $response->assertOk();
        $response->assertViewIs('admin.studyclub.create');
    }

    /** @test */
    public function it_can_store_new_edition(): void
    {
        $user = $this->actingAsAdmin();

        $response = $this->actingAs($user)->post('/admin_studyclub/store', [
            'number' => 15,
            'title' => 'Study Club #15',
            'description' => 'Description test',
            'publish_date' => '2026-05-15',
            'status' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('studyclub_editions', [
            'number' => 15,
            'title' => 'Study Club #15',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_on_store(): void
    {
        $user = $this->actingAsAdmin();

        $response = $this->actingAs($user)->post('/admin_studyclub/store', []);

        $response->assertSessionHasErrors(['number', 'title', 'publish_date']);
    }

    /** @test */
    public function it_validates_unique_number(): void
    {
        $user = $this->actingAsAdmin();
        StudyClubEdition::factory()->create(['number' => 10]);

        $response = $this->actingAs($user)->post('/admin_studyclub/store', [
            'number' => 10,
            'title' => 'Duplicate',
            'publish_date' => '2026-05-15',
        ]);

        $response->assertSessionHasErrors('number');
    }

    /** @test */
    public function it_shows_edit_form(): void
    {
        $user = $this->actingAsAdmin();
        $edition = StudyClubEdition::factory()->create();

        $response = $this->actingAs($user)->get("/admin_studyclub/edit/{$edition->id}");

        $response->assertOk();
        $response->assertViewIs('admin.studyclub.edit');
        $response->assertViewHas('edition');
    }

    /** @test */
    public function it_can_update_edition(): void
    {
        $user = $this->actingAsAdmin();
        $edition = StudyClubEdition::factory()->create([
            'title' => 'Old Title',
        ]);

        $response = $this->actingAs($user)->put("/admin_studyclub/update/{$edition->id}", [
            'number' => $edition->number,
            'title' => 'New Title',
            'description' => 'New Description',
            'publish_date' => $edition->publish_date->format('Y-m-d'),
            'status' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('studyclub_editions', [
            'id' => $edition->id,
            'title' => 'New Title',
        ]);
    }

    /** @test */
    public function it_can_delete_edition(): void
    {
        $user = $this->actingAsAdmin();
        $edition = StudyClubEdition::factory()->create();

        $response = $this->actingAs($user)->delete("/admin_studyclub/destroy/{$edition->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('studyclub_editions', [
            'id' => $edition->id,
        ]);
    }

    /** @test */
    public function it_can_add_item_to_edition(): void
    {
        $user = $this->actingAsAdmin();
        $edition = StudyClubEdition::factory()->create();

        $response = $this->actingAs($user)->post("/admin_studyclub/{$edition->id}/items", [
            'category' => 'ORTODONTIA',
            'type' => 'article',
            'type_label' => 'Artigo Original',
            'author' => 'Dr. Test',
            'title' => 'Test Article',
            'resumo' => 'Resumo test',
            'achados' => 'Achados test',
            'implicacoes' => 'Implicacoes test',
            'external_url' => 'https://test.com',
            'icon' => 'bi-journal-text',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('studyclub_items', [
            'edition_id' => $edition->id,
            'title' => 'Test Article',
        ]);
    }

    /** @test */
    public function it_can_upload_image_for_item(): void
    {
        $user = $this->actingAsAdmin();
        $edition = StudyClubEdition::factory()->create();
        $file = UploadedFile::fake()->image('article.jpg');

        $response = $this->actingAs($user)->post("/admin_studyclub/{$edition->id}/items", [
            'category' => 'ORTODONTIA',
            'type' => 'article',
            'type_label' => 'Artigo Original',
            'author' => 'Dr. Test',
            'title' => 'Test Article',
            'resumo' => 'Resumo test',
            'achados' => 'Achados test',
            'implicacoes' => 'Implicacoes test',
            'external_url' => 'https://test.com',
            'icon' => 'bi-journal-text',
            'image' => $file,
        ]);

        $response->assertRedirect();
        Storage::disk('public')->assertExists('studyclub/' . $file->hashName());
    }

    /** @test */
    public function it_can_delete_item(): void
    {
        $user = $this->actingAsAdmin();
        $edition = StudyClubEdition::factory()->create();
        $item = StudyClubItem::factory()->forEdition($edition)->create();

        $response = $this->actingAs($user)->delete("/admin_studyclub/items/{$item->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('studyclub_items', [
            'id' => $item->id,
        ]);
    }

    /** @test */
    public function it_shows_404_for_nonexistent_edition(): void
    {
        $user = $this->actingAsAdmin();

        $response = $this->actingAs($user)->get('/admin_studyclub/edit/99999');

        $response->assertNotFound();
    }
}
