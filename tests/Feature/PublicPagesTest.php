<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_load_successfully(): void
    {
        foreach (['/', '/services', '/projects', '/blog', '/team', '/contact', '/request-project'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_seo_endpoints_respond(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertSee('<urlset', false);
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap:');
    }

    public function test_language_switch_updates_session(): void
    {
        $this->get('/lang/en')->assertRedirect();
        $this->assertSame('en', session('locale'));
    }

    public function test_published_project_detail_is_visible_and_counts_views(): void
    {
        $project = Project::factory()->create(['status' => 'published', 'published_at' => now()]);

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee($project->translate('title'));

        $this->assertSame(1, $project->fresh()->views_count);
    }

    public function test_draft_project_returns_404(): void
    {
        $project = Project::factory()->draft()->create();

        $this->get(route('projects.show', $project))->assertNotFound();
    }
}
