<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::published()
            ->with(['technologies', 'categories'])
            ->when($request->filled('category') && $request->category !== 'all', function ($query) use ($request) {
                $query->whereHas('categories', fn ($q) => $q->where('slug', $request->category));
            })
            ->when($request->filled('technology'), function ($query) use ($request) {
                $query->whereHas('technologies', fn ($q) => $q->where('slug', $request->technology));
            })
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('projects', [
            'projects' => $projects,
            'categories' => Category::whereHas('projects', fn ($q) => $q->published())->orderBy('name')->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->status === 'published' && $project->published_at !== null, 404);

        $project->incrementViews();
        $project->load(['technologies', 'categories', 'tags', 'images', 'media']);

        $relatedProjects = Project::published()
            ->whereKeyNot($project->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $project->categories->pluck('id')))
            ->ordered()
            ->take(3)
            ->get();

        return view('project-details', compact('project', 'relatedProjects'));
    }
}
