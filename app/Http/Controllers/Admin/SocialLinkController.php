<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialLinkRequest;
use App\Models\SocialLink;
use App\Services\Admin\SocialLinkService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SocialLinkController extends Controller
{
    public function __construct(private readonly SocialLinkService $socialLinks)
    {
        $this->authorizeResource(SocialLink::class, 'social_link');
    }

    public function index(Request $request): View
    {
        return view('admin.social-links.index', [
            'socialLinks' => $this->socialLinks->paginate($request),
        ]);
    }

    public function create(): View
    {
        return view('admin.social-links.create');
    }

    public function store(SocialLinkRequest $request): RedirectResponse
    {
        $this->socialLinks->create($request->validated());

        return redirect()->route('admin.social-links.index')
            ->with('success', __('messages.created_successfully'));
    }

    public function edit(SocialLink $socialLink): View
    {
        return view('admin.social-links.edit', compact('socialLink'));
    }

    public function update(SocialLinkRequest $request, SocialLink $socialLink): RedirectResponse
    {
        $this->socialLinks->update($socialLink, $request->validated());

        return redirect()->route('admin.social-links.index')
            ->with('success', __('messages.updated_successfully'));
    }

    public function destroy(SocialLink $socialLink): RedirectResponse
    {
        $this->socialLinks->delete($socialLink);

        return redirect()->route('admin.social-links.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
