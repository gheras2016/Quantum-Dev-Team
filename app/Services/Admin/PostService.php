<?php

namespace App\Services\Admin;

use App\Models\Post;
use App\Services\Admin\Concerns\FiltersResources;
use App\Services\MediaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostService
{
    use FiltersResources;

    public function __construct(private readonly MediaService $media)
    {
    }

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Post::with('tags')->latest();

        $this->applySearch($query, $request, ['slug', 'title->en', 'title->ar']);
        $this->applyEquals($query, $request, ['status' => 'status']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $post = Post::create($this->attributes($data));
            $post->tags()->sync($data['tags'] ?? []);

            return $post;
        });
    }

    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $post->update($this->attributes($data, $post));
            $post->tags()->sync($data['tags'] ?? []);

            return $post;
        });
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }

    private function attributes(array $data, ?Post $post = null): array
    {
        $status = $data['status'] ?? 'draft';
        $publishedAt = $post?->published_at;

        if ($status === 'published' && $publishedAt === null) {
            $publishedAt = now();
        }

        return [
            'slug' => $data['slug'],
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'body' => $data['body'],
            'image' => $this->resolveImage($data, $post),
            'status' => $status,
            'featured' => (bool) ($data['featured'] ?? false),
            'published_at' => $publishedAt,
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
            'keywords' => $data['keywords'] ?? null,
            'user_id' => $post?->user_id ?? Auth::id(),
        ];
    }

    private function resolveImage(array $data, ?Post $post): ?string
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->media->delete($post?->image);

            return $this->media->store($data['image'], 'posts');
        }

        return $post?->image;
    }
}
