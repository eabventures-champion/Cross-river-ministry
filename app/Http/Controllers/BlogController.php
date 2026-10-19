<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        seo()
            ->title('Blog & Biblical Insights')
            ->description('Practical articles, spiritual reflections, and guidance for personal and family breakthrough.');

        $query = Post::published()->with(['category', 'author']);

        if ($request->filled('q')) {
            $term = $request->get('q');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('excerpt', 'like', "%{$term}%")
                  ->orWhere('body', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->get('category')));
        }

        $posts = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = PostCategory::withCount('posts')->get();
        $featured = Post::published()->where('is_featured', true)->latest('published_at')->first();

        return view('pages.blog.index', compact('posts', 'categories', 'featured'));
    }

    public function show(Post $post): View
    {
        seo()
            ->title($post->title)
            ->description($post->summary())
            ->image($post->imageUrl())
            ->type('article');

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('post_category_id', $post->post_category_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.blog.show', compact('post', 'relatedPosts'));
    }
}
