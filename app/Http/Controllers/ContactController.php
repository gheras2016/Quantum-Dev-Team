<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Notifications\NewContactNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        // Honeypot: silently accept bot submissions without persisting.
        if ($request->filled('website')) {
            return back()->with('success', __('messages.contact_success'));
        }

        $contact = Contact::create($request->validated());

        $this->notifyAdmin(new NewContactNotification($contact));

        return back()->with('success', __('messages.contact_success'));
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
