<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrashManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        return $user;
    }

    public function test_trashed_items_are_listed(): void
    {
        $admin = $this->admin();
        $project = Project::factory()->create();
        $project->delete();

        $this->actingAs($admin)
            ->get(route('admin.trash.index', 'projects'))
            ->assertOk()
            ->assertSee($project->activityLabel());
    }

    public function test_admin_can_restore_a_trashed_item(): void
    {
        $admin = $this->admin();
        $post = Post::factory()->create();
        $post->delete();

        $this->actingAs($admin)
            ->patch(route('admin.trash.restore', ['posts', $post->id]))
            ->assertRedirect();

        $this->assertNotSoftDeleted($post);
    }

    public function test_admin_can_permanently_delete(): void
    {
        $admin = $this->admin();
        $post = Post::factory()->create();
        $post->delete();

        $this->actingAs($admin)
            ->delete(route('admin.trash.destroy', ['posts', $post->id]))
            ->assertRedirect();

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_editor_cannot_access_trash(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $editor = User::factory()->create();
        $editor->assignRole('editor');

        $this->actingAs($editor)
            ->get(route('admin.trash.index', 'projects'))
            ->assertForbidden();
    }

    public function test_unknown_resource_returns_404(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.trash.index', 'widgets'))
            ->assertNotFound();
    }
}
