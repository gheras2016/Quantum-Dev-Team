<?php

namespace App\Services\Admin;

use App\Models\SocialLink;
use App\Services\Admin\Concerns\FiltersResources;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class SocialLinkService
{
    use FiltersResources;

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = SocialLink::query()->ordered();

        $this->applySearch($query, $request, ['platform', 'url']);
        $this->applyEquals($query, $request, ['is_active' => 'is_active']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): SocialLink
    {
        return SocialLink::create($this->attributes($data));
    }

    public function update(SocialLink $socialLink, array $data): SocialLink
    {
        $socialLink->update($this->attributes($data));

        return $socialLink;
    }

    public function delete(SocialLink $socialLink): void
    {
        $socialLink->delete();
    }

    private function attributes(array $data): array
    {
        return [
            'platform' => $data['platform'],
            'url' => $data['url'],
            'icon' => $data['icon'] ?? null,
            'order' => $data['order'] ?? 0,
            'is_active' => (bool) ($data['is_active'] ?? false),
        ];
    }
}
