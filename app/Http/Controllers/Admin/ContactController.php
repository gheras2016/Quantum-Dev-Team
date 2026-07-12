<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ContactsExport;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Services\Admin\Concerns\FiltersResources;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ContactController extends Controller
{
    use FiltersResources;

    public function export(): BinaryFileResponse
    {
        $this->authorize('viewAny', Contact::class);

        return Excel::download(new ContactsExport, 'contacts-'.now()->format('Y-m-d').'.xlsx');
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Contact::class);

        $query = Contact::query()->latest();
        $this->applySearch($query, $request, ['name', 'email', 'subject']);
        $this->applyEquals($query, $request, ['is_read' => 'is_read', 'status' => 'status']);

        return view('admin.contacts.index', [
            'contacts' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function show(Contact $contact): View
    {
        $this->authorize('view', $contact);

        $contact->markAsRead();

        return view('admin.contacts.show', compact('contact'));
    }

    public function markAsRead(Contact $contact): RedirectResponse
    {
        $this->authorize('update', $contact);

        $contact->markAsRead();

        return back()->with('success', __('messages.marked_as_read'));
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $this->authorize('delete', $contact);

        $contact->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', __('messages.deleted_successfully'));
    }
}
