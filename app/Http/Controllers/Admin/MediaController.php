<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;

class MediaController extends Controller
{
    public function __construct(private readonly MediaService $media)
    {
    }

    public function destroy(Media $media): RedirectResponse
    {
        $owner = $media->model;

        // Reuse the owning model's policy (e.g. a Project's "update" ability).
        if ($owner !== null) {
            $this->authorize('update', $owner);
        } else {
            abort_unless(auth()->user()->isAdmin(), 403);
        }

        $this->media->delete($media->file_name);
        $media->delete();

        return back()->with('success', __('messages.deleted_successfully'));
    }
}
