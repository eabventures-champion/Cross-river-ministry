<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use App\Models\PodcastEpisode;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PodcastController extends Controller
{
    public function index(Request $request): View
    {
        seo()
            ->title('Podcasts & Audio Messages')
            ->description('Listen to practical biblical insights and transformative discussions on crossing over life’s challenges.');

        $query = PodcastEpisode::published()->with('podcast');

        if ($request->filled('q')) {
            $term = $request->get('q');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('excerpt', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        if ($request->filled('podcast')) {
            $query->whereHas('podcast', fn ($q) => $q->where('slug', $request->get('podcast')));
        }

        $episodes = $query->latest('published_at')->paginate(9)->withQueryString();
        $podcasts = Podcast::withCount('episodes')->get();
        $featured = PodcastEpisode::published()->where('is_featured', true)->latest('published_at')->first();

        return view('pages.podcasts.index', compact('episodes', 'podcasts', 'featured'));
    }

    public function show(PodcastEpisode $episode): View
    {
        seo()
            ->title($episode->title)
            ->description($episode->summary())
            ->image($episode->artworkUrl());

        $relatedEpisodes = PodcastEpisode::published()
            ->where('id', '!=', $episode->id)
            ->where('podcast_id', $episode->podcast_id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.podcasts.show', compact('episode', 'relatedEpisodes'));
    }
}
