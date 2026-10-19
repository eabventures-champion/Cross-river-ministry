<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Request $request): View
    {
        seo()
            ->title('Video Media Library')
            ->description('Watch inspiring messages, spiritual reflections, and practical life coaching videos from Cross Rivers Ministries.');

        $query = Video::published()->with('category');

        if ($request->filled('q')) {
            $term = $request->get('q');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('excerpt', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->get('category')));
        }

        $videos = $query->latest('published_at')->paginate(9)->withQueryString();
        $categories = VideoCategory::withCount('videos')->get();
        $featured = Video::published()->where('is_featured', true)->latest('published_at')->first()
            ?? Video::published()->latest('published_at')->first();

        return view('pages.videos.index', compact('videos', 'categories', 'featured'));
    }

    public function show(Video $video): View
    {
        seo()
            ->title($video->title)
            ->description($video->summary())
            ->image($video->thumbnailUrl());

        $relatedVideos = Video::published()
            ->where('id', '!=', $video->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.videos.show', compact('video', 'relatedVideos'));
    }
}
