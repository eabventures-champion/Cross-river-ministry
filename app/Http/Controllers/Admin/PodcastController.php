<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use App\Models\PodcastEpisode;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PodcastController extends Controller
{
    public function index(): View
    {
        $episodes = PodcastEpisode::with('podcast')->latest()->paginate(15);
        $podcasts = Podcast::all();

        return view('admin.podcasts.index', compact('episodes', 'podcasts'));
    }

    public function create(): View
    {
        $episode = new PodcastEpisode();
        $podcasts = Podcast::all();

        return view('admin.podcasts.form', compact('episode', 'podcasts'));
    }

    public function store(Request $request, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'podcast_id' => ['required', 'exists:podcasts,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:podcast_episodes,slug'],
            'episode_number' => ['nullable', 'integer'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'audio_url' => ['nullable', 'url', 'max:500'],
            'image' => ['nullable', 'image', 'max:5120'],
            'duration' => ['nullable', 'string', 'max:20'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'podcasts');
        }

        $validated['is_featured'] = $request->boolean('is_featured', false);

        PodcastEpisode::create($validated);

        return redirect()->route('admin.podcasts.index')->with('success', 'Episode created.');
    }

    public function edit(PodcastEpisode $podcast): View
    {
        $episode = $podcast;
        $podcasts = Podcast::all();

        return view('admin.podcasts.form', compact('episode', 'podcasts'));
    }

    public function update(Request $request, PodcastEpisode $podcast, MediaService $media): RedirectResponse
    {
        $episode = $podcast;

        $validated = $request->validate([
            'podcast_id' => ['required', 'exists:podcasts,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:podcast_episodes,slug,' . $episode->id],
            'episode_number' => ['nullable', 'integer'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'audio_url' => ['nullable', 'url', 'max:500'],
            'image' => ['nullable', 'image', 'max:5120'],
            'duration' => ['nullable', 'string', 'max:20'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'podcasts');
        }

        $validated['is_featured'] = $request->boolean('is_featured', false);

        $episode->update($validated);

        return redirect()->route('admin.podcasts.index')->with('success', 'Episode updated.');
    }

    public function destroy(PodcastEpisode $podcast): RedirectResponse
    {
        $podcast->delete();

        return redirect()->route('admin.podcasts.index')->with('success', 'Episode deleted.');
    }
}
