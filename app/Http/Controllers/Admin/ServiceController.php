<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Services\Admin\ServiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(private readonly ServiceService $services)
    {
        $this->authorizeResource(Service::class, 'service');
    }

    public function index(Request $request): View
    {
        return view('admin.services.index', [
            'services' => $this->services->paginate($request),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $this->services->create($request->validated());

        return redirect()->route('admin.services.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(Service $service): View
    {
        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $this->services->update($service, $request->validated());

        return redirect()->route('admin.services.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->services->delete($service);

        return redirect()->route('admin.services.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
