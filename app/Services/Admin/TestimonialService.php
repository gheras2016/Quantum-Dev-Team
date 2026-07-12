<?php

namespace App\Services\Admin;

use App\Models\Testimonial;
use App\Services\Admin\Concerns\FiltersResources;
use App\Services\MediaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class TestimonialService
{
    use FiltersResources;

    public function __construct(private readonly MediaService $media)
    {
    }

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Testimonial::query()->ordered();

        $this->applySearch($query, $request, ['author_name', 'author_company']);
        $this->applyEquals($query, $request, ['is_active' => 'is_active']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): Testimonial
    {
        return Testimonial::create($this->attributes($data));
    }

    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        $testimonial->update($this->attributes($data, $testimonial));

        return $testimonial;
    }

    public function delete(Testimonial $testimonial): void
    {
        $testimonial->delete();
    }

    private function attributes(array $data, ?Testimonial $testimonial = null): array
    {
        return [
            'author_name' => $data['author_name'],
            'author_title' => $data['author_title'] ?? null,
            'author_company' => $data['author_company'] ?? null,
            'avatar' => $this->resolveAvatar($data, $testimonial),
            'content' => $data['content'],
            'rating' => $data['rating'] ?? 5,
            'order' => $data['order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
    }

    private function resolveAvatar(array $data, ?Testimonial $testimonial): ?string
    {
        if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
            $this->media->delete($testimonial?->avatar);

            return $this->media->store($data['avatar'], 'testimonials');
        }

        return $testimonial?->avatar;
    }
}
