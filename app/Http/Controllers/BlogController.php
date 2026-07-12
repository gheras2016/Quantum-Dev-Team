<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::published()
            ->with('tags')
            ->when($request->filled('search'), fn ($q) => $q->search($request->search))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', [
            'posts' => $posts,
            'featured' => Post::published()->featured()->latest('published_at')->first(),
        ]);
    }

    public function show(Post $post): View
    {
        abort_unless($post->status === 'published' && $post->published_at !== null, 404);

        $post->incrementViews();
        $post->load('tags');

        $related = Post::published()
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }
}
