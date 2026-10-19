<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with(['category', 'author'])->latest()->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $post = new Post();
        $categories = PostCategory::all();

        return view('admin.posts.form', compact('post', 'categories'));
    }

    public function store(Request $request, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'post_category_id' => ['nullable', 'exists:post_categories,id'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'posts');
        }

        $validated['user_id'] = auth()->id();
        $validated['is_featured'] = $request->boolean('is_featured', false);

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post created.');
    }

    public function edit(Post $post): View
    {
        $categories = PostCategory::all();

        return view('admin.posts.form', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $post->id],
            'post_category_id' => ['nullable', 'exists:post_categories,id'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'posts');
        }

        $validated['is_featured'] = $request->boolean('is_featured', false);

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }
}
