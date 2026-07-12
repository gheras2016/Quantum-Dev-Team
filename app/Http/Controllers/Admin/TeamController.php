<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeamMemberRequest;
use App\Models\TeamMember;
use App\Services\Admin\TeamMemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function __construct(private readonly TeamMemberService $members)
    {
        $this->authorizeResource(TeamMember::class, 'team');
    }

    public function index(Request $request): View
    {
        return view('admin.team.index', [
            'members' => $this->members->paginate($request),
        ]);
    }

    public function create(): View
    {
        return view('admin.team.create');
    }

    public function store(TeamMemberRequest $request): RedirectResponse
    {
        $this->members->create($request->validated());

        return redirect()->route('admin.team.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function show(TeamMember $team): View
    {
        return view('admin.team.show', ['member' => $team]);
    }

    public function edit(TeamMember $team): View
    {
        return view('admin.team.edit', ['member' => $team]);
    }

    public function update(TeamMemberRequest $request, TeamMember $team): RedirectResponse
    {
        $this->members->update($team, $request->validated());

        return redirect()->route('admin.team.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(TeamMember $team): RedirectResponse
    {
        $this->members->delete($team);

        return redirect()->route('admin.team.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
