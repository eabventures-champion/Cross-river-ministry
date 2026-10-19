<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        $videos = Video::with('category')->latest()->paginate(15);

        return view('admin.videos.index', compact('videos'));
    }

    public function create(): View
    {
        $video = new Video();
        $categories = VideoCategory::all();

        return view('admin.videos.form', compact('video', 'categories'));
    }

    public function store(Request $request, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'video_category_id' => ['nullable', 'exists:video_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:videos,slug'],
            'video_url' => ['required', 'url', 'max:500'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'duration' => ['nullable', 'string', 'max:20'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $media->storePath($request->file('thumbnail'), 'videos');
        }

        $validated['is_featured'] = $request->boolean('is_featured', false);

        Video::create($validated);

        return redirect()->route('admin.videos.index')->with('success', 'Video added.');
    }

    public function edit(Video $video): View
    {
        $categories = VideoCategory::all();

        return view('admin.videos.form', compact('video', 'categories'));
    }

    public function update(Request $request, Video $video, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'video_category_id' => ['nullable', 'exists:video_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:videos,slug,' . $video->id],
            'video_url' => ['required', 'url', 'max:500'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'max:5120'],
            'duration' => ['nullable', 'string', 'max:20'],
            'is_featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $media->storePath($request->file('thumbnail'), 'videos');
        }

        $validated['is_featured'] = $request->boolean('is_featured', false);

        $video->update($validated);

        return redirect()->route('admin.videos.index')->with('success', 'Video updated.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video deleted.');
    }
}
