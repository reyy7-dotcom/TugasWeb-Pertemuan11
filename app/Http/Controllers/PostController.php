<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of blog posts.
     */
    public function index(): View
    {
        $posts = Post::query()
            ->published()
            ->with('user')
            ->recent()
            ->paginate(9);

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        Gate::authorize('create', Post::class);

        return view('posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image_url' => ['nullable', 'url'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($validated['content']), 150),
            'content' => $validated['content'],
            'image_url' => $validated['image_url'] ?? 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&auto=format&fit=crop&q=80',
            'is_published' => $request->boolean('is_published', true),
            'published_at' => $request->boolean('is_published', true) ? now() : null,
        ]);

        return redirect()->route('posts.show', $post)
            ->with('status', 'Artikel berhasil diterbitkan!');
    }

    /**
     * Display the specified post.
     */
    public function show(Post $post): View
    {
        Gate::authorize('view', $post);

        $post->load('user');

        $recentPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->with('user')
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('posts.show', compact('post', 'recentPosts'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image_url' => ['nullable', 'url'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $post->update([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'image_url' => $validated['image_url'] ?? $post->image_url,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('posts.show', $post)
            ->with('status', 'Artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')
            ->with('status', 'Artikel berhasil dihapus!');
    }
}
