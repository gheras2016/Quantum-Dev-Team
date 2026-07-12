<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechnologyRequest;
use App\Models\Technology;
use App\Services\Admin\TechnologyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechnologyController extends Controller
{
    public function __construct(private readonly TechnologyService $technologies)
    {
        $this->authorizeResource(Technology::class, 'technology');
    }

    public function index(Request $request): View
    {
        return view('admin.technologies.index', [
            'technologies' => $this->technologies->paginate($request),
        ]);
    }

    public function create(): View
    {
        return view('admin.technologies.create');
    }

    public function store(TechnologyRequest $request): RedirectResponse
    {
        $this->technologies->create($request->validated());

        return redirect()->route('admin.technologies.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function edit(Technology $technology): View
    {
        return view('admin.technologies.edit', compact('technology'));
    }

    public function update(TechnologyRequest $request, Technology $technology): RedirectResponse
    {
        $this->technologies->update($technology, $request->validated());

        return redirect()->route('admin.technologies.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Technology $technology): RedirectResponse
    {
        $this->technologies->delete($technology);

        return redirect()->route('admin.technologies.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
