<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        $media = Media::latest()->paginate(24);

        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request, MediaService $service): JsonResponse|RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'max:20480', 'mimes:jpeg,jpg,png,webp,gif,mp3,wav,pdf'],
            'alt' => ['nullable', 'string', 'max:255'],
        ]);

        $item = $service->store($request->file('file'), 'library', $request->input('alt'));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'media' => $item,
                'url' => $item->url(),
            ]);
        }

        return back()->with('success', 'File uploaded successfully.');
    }

    public function destroy(Media $media, MediaService $service): RedirectResponse
    {
        $service->delete($media);

        return back()->with('success', 'Media deleted.');
    }
}
