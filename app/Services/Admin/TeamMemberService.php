<?php

namespace App\Services\Admin;

use App\Models\TeamMember;
use App\Services\Admin\Concerns\FiltersResources;
use App\Services\MediaService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class TeamMemberService
{
    use FiltersResources;

    public function __construct(private readonly MediaService $media)
    {
    }

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = TeamMember::query()->ordered();

        $this->applySearch($query, $request, ['name', 'role', 'email']);
        $this->applyEquals($query, $request, ['status' => 'status', 'is_active' => 'is_active']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): TeamMember
    {
        return TeamMember::create($this->attributes($data));
    }

    public function update(TeamMember $member, array $data): TeamMember
    {
        $member->update($this->attributes($data, $member));

        return $member;
    }

    public function delete(TeamMember $member): void
    {
        $member->delete();
    }

    private function attributes(array $data, ?TeamMember $member = null): array
    {
        return [
            'name' => $data['name'],
            'role' => $data['role'],
            'bio' => $data['bio'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'github_url' => $data['github_url'] ?? null,
            'image' => $this->resolveImage($data, $member),
            'skills' => $data['skills'] ?? [],
            'order' => $data['order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
            'status' => $data['status'] ?? 'published',
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
            'keywords' => $data['keywords'] ?? null,
        ];
    }

    private function resolveImage(array $data, ?TeamMember $member): ?string
    {
        if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
            $this->media->delete($member?->image);

            return $this->media->store($data['image'], 'team');
        }

        return $member?->image;
    }
}
