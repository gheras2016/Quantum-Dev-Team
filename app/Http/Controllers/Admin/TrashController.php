<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TrashController extends Controller
{
    /**
     * resource key => [model class, permission suffix, image column|null].
     */
    private array $map = [
        'projects' => [Project::class, 'projects', 'image'],
        'posts' => [Post::class, 'posts', 'image'],
        'services' => [Service::class, 'services', 'image'],
        'team' => [TeamMember::class, 'team', 'image'],
        'testimonials' => [Testimonial::class, 'testimonials', 'avatar'],
        'faqs' => [Faq::class, 'faqs', null],
    ];

    public function index(string $resource): View
    {
        [$class, $permission] = $this->resolve($resource);
        $this->authorize('delete', $class);

        return view('admin.trash.index', [
            'resource' => $resource,
            'resources' => $this->availableResources(),
            'items' => $class::onlyTrashed()->latest('deleted_at')->paginate(15),
        ]);
    }

    public function restore(string $resource, int $id): RedirectResponse
    {
        [$class] = $this->resolve($resource);
        $this->authorize('delete', $class);

        $this->trashedModel($class, $id)->restore();

        return back()->with('success', __('messages.restored_successfully'));
    }

    public function forceDelete(string $resource, int $id, MediaService $media): RedirectResponse
    {
        [$class, , $imageColumn] = $this->resolve($resource);
        $this->authorize('delete', $class);

        $model = $this->trashedModel($class, $id);

        if ($imageColumn && $model->{$imageColumn}) {
            $media->delete($model->{$imageColumn});
        }

        $model->forceDelete();

        return back()->with('success', __('messages.permanently_deleted'));
    }

    private function resolve(string $resource): array
    {
        abort_unless(isset($this->map[$resource]), 404);

        return $this->map[$resource];
    }

    private function trashedModel(string $class, int $id): Model
    {
        return $class::onlyTrashed()->findOrFail($id);
    }

    /**
     * Resources the current user is allowed to manage the trash for.
     */
    private function availableResources(): array
    {
        return collect($this->map)
            ->filter(fn ($config) => auth()->user()->can('delete_'.$config[1]))
            ->keys()
            ->all();
    }
}
