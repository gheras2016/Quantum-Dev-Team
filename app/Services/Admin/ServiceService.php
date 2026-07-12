<?php

namespace App\Services\Admin;

use App\Models\Service;
use App\Services\Admin\Concerns\FiltersResources;
use App\Services\MediaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class ServiceService
{
    use FiltersResources;

    public function __construct(private readonly MediaService $media)
    {
    }

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Service::query()->ordered();

        $this->applySearch($query, $request, ['slug', 'title->en', 'title->ar']);
        $this->applyEquals($query, $request, ['status' => 'status', 'is_active' => 'is_active']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): Service
    {
        return Service::create($this->attributes($data));
    }

    public function update(Service $service, array $data): Service
    {
        $service->update($this->attributes($data, $service));

        return $service;
    }

    public function delete(Service $service): void
    {
        $service->delete();
    }

    private function attributes(array $data, ?Service $service = null): array
    {
        return [
            'slug' => $data['slug'],
            'title' => $data['title'],
            'description' => $data['description'],
            'icon' => $data['icon'] ?? $service?->icon,
            'image' => $this->resolveImage($data, $service),
            'order' => $data['order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'status' => $data['status'] ?? 'published',
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
            'keywords' => $data['keywords'] ?? null,
            'user_id' => $service?->user_id ?? Auth::id(),
        ];
    }

    private function resolveImage(array $data, ?Service $service): ?string
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->media->delete($service?->image);

            return $this->media->store($data['image'], 'services');
        }

        return $service?->image;
    }
}
