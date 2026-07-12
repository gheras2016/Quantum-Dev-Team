<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['loc' => route('home'), 'priority' => '1.0'],
            ['loc' => route('services'), 'priority' => '0.8'],
            ['loc' => route('projects'), 'priority' => '0.8'],
            ['loc' => route('team'), 'priority' => '0.6'],
            ['loc' => route('blog'), 'priority' => '0.7'],
            ['loc' => route('contact'), 'priority' => '0.6'],
            ['loc' => route('request-project'), 'priority' => '0.7'],
        ];

        foreach (Project::published()->latest('published_at')->get() as $project) {
            $urls[] = [
                'loc' => route('projects.show', $project),
                'lastmod' => $project->updated_at->toAtomString(),
                'priority' => '0.7',
            ];
        }

        foreach (Post::published()->latest('published_at')->get() as $post) {
            $urls[] = [
                'loc' => route('blog.show', $post),
                'lastmod' => $post->updated_at->toAtomString(),
                'priority' => '0.6',
            ];
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nAllow: /\nDisallow: /admin\n\nSitemap: ".url('sitemap.xml')."\n";

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
