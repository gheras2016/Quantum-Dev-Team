<?php

namespace App\Services\Admin;

use App\Models\Technology;
use App\Services\Admin\Concerns\FiltersResources;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class TechnologyService
{
    use FiltersResources;

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Technology::query()->latest();

        $this->applySearch($query, $request, ['name', 'slug', 'description']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): Technology
    {
        return Technology::create($this->attributes($data));
    }

    public function update(Technology $technology, array $data): Technology
    {
        $technology->update($this->attributes($data));

        return $technology;
    }

    public function delete(Technology $technology): void
    {
        $technology->delete();
    }

    private function attributes(array $data): array
    {
        return [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'icon' => $data['icon'] ?? null,
            'description' => $data['description'] ?? null,
        ];
    }
}
