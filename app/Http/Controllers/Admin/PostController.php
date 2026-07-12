<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use App\Services\Admin\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private readonly PostService $posts)
    {
        $this->authorizeResource(Post::class, 'post');
    }

    public function index(Request $request): View
    {
        return view('admin.posts.index', ['posts' => $this->posts->paginate($request)]);
    }

    public function create(): View
    {
        return view('admin.posts.create', ['tags' => Tag::orderBy('name')->get()]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $this->posts->create($request->validated());

        return redirect()->route('admin.posts.index')->with('success', __('messages.created_successfully'));
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post->load('tags'),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $this->posts->update($post, $request->validated());

        return redirect()->route('admin.posts.index')->with('success', __('messages.updated_successfully'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->posts->delete($post);

        return redirect()->route('admin.posts.index')->with('success', __('messages.deleted_successfully'));
    }
}
