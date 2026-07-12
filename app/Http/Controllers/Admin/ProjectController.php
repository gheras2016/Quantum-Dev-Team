<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Technology;
use App\Services\Admin\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private readonly ProjectService $projects)
    {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(Request $request): View
    {
        return view('admin.projects.index', [
            'projects' => $this->projects->paginate($request),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', $this->formData());
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $this->projects->create($request->validated());

        return redirect()->route('admin.projects.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(Project $project): View
    {
        $project->load(['technologies', 'categories', 'tags', 'media']);

        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.edit', array_merge($this->formData(), [
            'project' => $project->load(['technologies', 'categories', 'tags', 'media']),
        ]));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->projects->update($project, $request->validated());

        return redirect()->route('admin.projects.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->projects->delete($project);

        return redirect()->route('admin.projects.index')
            ->with('success', __('messages.deleted_successfully'));
    }

    private function formData(): array
    {
        return [
            'technologies' => Technology::orderBy('name')->get(),
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ];
    }
}
