<?php

namespace App\Services\Admin;

use App\Models\Category;
use App\Services\Admin\Concerns\FiltersResources;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CategoryService
{
    use FiltersResources;

    public function paginate(Request $request): LengthAwarePaginator
    {
        $query = Category::query()->latest();

        $this->applySearch($query, $request, ['name', 'slug', 'description']);
        $this->applyEquals($query, $request, ['type' => 'type']);

        return $query->paginate(15)->withQueryString();
    }

    public function create(array $data): Category
    {
        return Category::create($this->attributes($data));
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($this->attributes($data));

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }

    private function attributes(array $data): array
    {
        return [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'type' => $data['type'] ?? null,
            'description' => $data['description'] ?? null,
        ];
    }
}
