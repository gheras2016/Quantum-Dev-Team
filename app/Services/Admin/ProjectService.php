<?php

namespace App\Services\Admin;

use App\Models\Project;
use App\Services\Admin\Concerns\FiltersResources;
use App\Services\MediaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    use FiltersResources;

    public function __construct(private readonly MediaService $media)
    {
    }

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Project::with(['technologies', 'categories', 'tags'])->latest();

        $this->applySearch($query, $request, ['slug', 'client_name']);
        $this->applyEquals($query, $request, ['status' => 'status', 'featured' => 'featured']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): Project
    {
        return DB::transaction(function () use ($data) {
            $project = Project::create($this->attributes($data));
            $this->syncRelations($project, $data);
            $this->handleUploads($project, $data);

            return $project;
        });
    }

    public function update(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {
            $project->update($this->attributes($data, $project));
            $this->syncRelations($project, $data);
            $this->handleUploads($project, $data);

            return $project;
        });
    }

    public function delete(Project $project): void
    {
        $project->delete();
    }

    private function attributes(array $data, ?Project $project = null): array
    {
        $status = $data['status'] ?? 'draft';
        $publishedAt = $project?->published_at;

        if ($status === 'published' && $publishedAt === null) {
            $publishedAt = now();
        }

        return [
            'slug' => $data['slug'],
            'title' => $data['title'],
            'description' => $data['description'],
            'client_name' => $data['client_name'] ?? null,
            'project_url' => $data['project_url'] ?? null,
            'image' => $this->resolveImage($data, $project),
            'duration' => $data['duration'] ?? null,
            'progress' => $data['progress'] ?? 0,
            'case_study' => $data['case_study'] ?? null,
            'github_url' => $data['github_url'] ?? null,
            'demo_url' => $data['demo_url'] ?? null,
            'youtube_url' => $data['youtube_url'] ?? null,
            'documentation_url' => $data['documentation_url'] ?? null,
            'status' => $status,
            'featured' => (bool) ($data['featured'] ?? false),
            'published_at' => $publishedAt,
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
            'keywords' => $data['keywords'] ?? null,
            'user_id' => $project?->user_id ?? Auth::id(),
        ];
    }

    private function resolveImage(array $data, ?Project $project): ?string
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $this->media->delete($project?->image);

            return $this->media->store($data['image'], 'projects');
        }

        return $project?->image;
    }

    private function syncRelations(Project $project, array $data): void
    {
        $project->technologies()->sync($data['technologies'] ?? []);
        $project->categories()->sync($data['categories'] ?? []);
        $project->tags()->sync($data['tags'] ?? []);
    }

    private function handleUploads(Project $project, array $data): void
    {
        if (! empty($data['media'])) {
            $this->media->attachMany($project, $data['media'], 'project_images', 'projects');
        }
    }
}
