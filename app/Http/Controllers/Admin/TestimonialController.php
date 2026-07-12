<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialRequest;
use App\Models\Testimonial;
use App\Services\Admin\TestimonialService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __construct(private readonly TestimonialService $testimonials)
    {
        $this->authorizeResource(Testimonial::class, 'testimonial');
    }

    public function index(Request $request): View
    {
        return view('admin.testimonials.index', ['testimonials' => $this->testimonials->paginate($request)]);
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(TestimonialRequest $request): RedirectResponse
    {
        $this->testimonials->create($request->validated());

        return redirect()->route('admin.testimonials.index')->with('success', __('messages.created_successfully'));
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(TestimonialRequest $request, Testimonial $testimonial): RedirectResponse
    {
        $this->testimonials->update($testimonial, $request->validated());

        return redirect()->route('admin.testimonials.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $this->testimonials->delete($testimonial);

        return redirect()->route('admin.testimonials.index')->with('success', __('messages.deleted_successfully'));
    }
}
