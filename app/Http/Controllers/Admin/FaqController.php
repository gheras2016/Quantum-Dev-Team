<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FaqRequest;
use App\Models\Faq;
use App\Services\Admin\FaqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct(private readonly FaqService $faqs)
    {
        $this->authorizeResource(Faq::class, 'faq');
    }

    public function index(Request $request): View
    {
        return view('admin.faqs.index', ['faqs' => $this->faqs->paginate($request)]);
    }

    public function create(): View
    {
        return view('admin.faqs.create');
    }

    public function store(FaqRequest $request): RedirectResponse
    {
        $this->faqs->create($request->validated());

        return redirect()->route('admin.faqs.index')->with('success', __('messages.created_successfully'));
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(FaqRequest $request, Faq $faq): RedirectResponse
    {
        $this->faqs->update($faq, $request->validated());

        return redirect()->route('admin.faqs.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $this->faqs->delete($faq);

        return redirect()->route('admin.faqs.index')->with('success', __('messages.deleted_successfully'));
    }
}
