<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        return $user;
    }

    public function test_admin_can_create_a_project(): void
    {
        Storage::fake('public');

        $payload = [
            'slug' => 'my-new-project',
            'title' => ['ar' => 'مشروع', 'en' => 'My New Project'],
            'description' => ['ar' => 'وصف', 'en' => 'A description'],
            'status' => 'published',
            'image' => UploadedFile::fake()->image('cover.jpg'),
            'media' => [UploadedFile::fake()->image('g1.jpg'), UploadedFile::fake()->image('g2.jpg')],
        ];

        $this->actingAs($this->admin())
            ->post(route('admin.projects.store'), $payload)
            ->assertRedirect(route('admin.projects.index'));

        $project = Project::firstWhere('slug', 'my-new-project');

        $this->assertNotNull($project);
        $this->assertNotNull($project->published_at);
        $this->assertCount(2, $project->media);           // gallery uploaded
        Storage::disk('public')->assertExists($project->image);
    }

    public function test_project_creation_requires_title(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.projects.store'), ['slug' => 'x'])
            ->assertSessionHasErrors(['title.ar', 'title.en', 'description.ar', 'description.en']);
    }

    public function test_admin_can_delete_a_gallery_image(): void
    {
        Storage::fake('public');
        $admin = $this->admin();

        $project = \App\Models\Project::factory()->create();
        $media = $project->media()->create([
            'collection_name' => 'project_images',
            'name' => 'g.jpg',
            'file_name' => 'projects/g.jpg',
            'disk' => 'public',
            'size' => 100,
        ]);
        Storage::disk('public')->put('projects/g.jpg', 'x');

        $this->actingAs($admin)
            ->delete(route('admin.media.destroy', $media))
            ->assertRedirect();

        $this->assertSoftDeleted($media);
        Storage::disk('public')->assertMissing('projects/g.jpg');
    }

    public function test_activity_is_logged_on_creation(): void
    {
        $this->actingAs($this->admin())->post(route('admin.projects.store'), [
            'slug' => 'logged-project',
            'title' => ['ar' => 'أ', 'en' => 'Logged'],
            'description' => ['ar' => 'ب', 'en' => 'Body'],
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('activity_log', ['description' => 'Created Project: logged-project']);
    }
}
