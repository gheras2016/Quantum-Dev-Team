<?php

namespace App\Services\Admin\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Small helpers to keep the "index" query building in admin services
 * consistent and free of repetition.
 */
trait FiltersResources
{
    protected function applySearch(Builder $query, Request $request, array $columns): Builder
    {
        $search = trim((string) $request->get('search'));

        if ($search !== '') {
            $query->where(function (Builder $q) use ($columns, $search) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        return $query;
    }

    protected function applyEquals(Builder $query, Request $request, array $filters): Builder
    {
        foreach ($filters as $param => $column) {
            if ($request->filled($param)) {
                $query->where($column, $request->get($param));
            }
        }

        return $query;
    }
}
