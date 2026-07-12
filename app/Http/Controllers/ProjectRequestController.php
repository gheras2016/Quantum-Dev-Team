<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequestFormRequest;
use App\Models\ProjectRequest;
use App\Notifications\NewProjectRequestNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ProjectRequestController extends Controller
{
    public function index(): View
    {
        return view('request-project');
    }

    public function store(ProjectRequestFormRequest $request): RedirectResponse
    {
        // Honeypot: silently accept bot submissions without persisting.
        if ($request->filled('website')) {
            return back()->with('success', __('messages.project_request_success'));
        }

        $projectRequest = ProjectRequest::create($request->validated());

        $this->notifyAdmin(new NewProjectRequestNotification($projectRequest));

        return back()->with('success', __('messages.project_request_success'));
    }

    private function notifyAdmin($notification): void
    {
        $recipient = setting('site_email', config('mail.from.address'));

        if (blank($recipient)) {
            return;
        }

        try {
            Notification::route('mail', $recipient)->notify($notification);
        } catch (\Throwable $e) {
            Log::warning('Admin notification failed: '.$e->getMessage());
        }
    }
}
