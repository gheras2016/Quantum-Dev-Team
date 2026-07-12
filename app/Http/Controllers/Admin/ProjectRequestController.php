<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProjectRequestsExport;
use App\Http\Controllers\Controller;
use App\Models\ProjectRequest;
use App\Services\Admin\Concerns\FiltersResources;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProjectRequestController extends Controller
{
    use FiltersResources;

    public function index(Request $request): View
    {
        $this->authorize('viewAny', ProjectRequest::class);

        $query = ProjectRequest::query()->latest();
        $this->applySearch($query, $request, ['name', 'email', 'project_type']);
        $this->applyEquals($query, $request, ['status' => 'status']);

        return view('admin.project-requests.index', [
            'projectRequests' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $this->authorize('viewAny', ProjectRequest::class);

        return Excel::download(new ProjectRequestsExport, 'project-requests-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportPdf(ProjectRequest $projectRequest): Response
    {
        $this->authorize('view', $projectRequest);

        return Pdf::loadView('admin.project-requests.pdf', compact('projectRequest'))
            ->download('project-request-'.$projectRequest->id.'.pdf');
    }

    public function show(ProjectRequest $projectRequest): View
    {
        $this->authorize('view', $projectRequest);

        $projectRequest->markAsRead();

        return view('admin.project-requests.show', compact('projectRequest'));
    }

    public function updateStatus(Request $request, ProjectRequest $projectRequest): RedirectResponse
    {
        $this->authorize('update', $projectRequest);

        $data = $request->validate([
            'status' => ['required', Rule::in(ProjectRequest::STATUSES)],
        ]);

        $projectRequest->update($data);

        return back()->with('success', __('messages.status_updated'));
    }

    public function destroy(ProjectRequest $projectRequest): RedirectResponse
    {
        $this->authorize('delete', $projectRequest);

        $projectRequest->delete();

        return redirect()->route('admin.project-requests.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
