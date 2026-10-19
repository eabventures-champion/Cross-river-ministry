<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoachingService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoachingServiceController extends Controller
{
    public function index(): View
    {
        $services = CoachingService::orderBy('sort_order')->paginate(15);

        return view('admin.coaching.index', compact('services'));
    }

    public function create(): View
    {
        $service = new CoachingService();

        return view('admin.coaching.form', compact('service'));
    }

    public function store(Request $request, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:coaching_services,slug'],
            'icon' => ['nullable', 'string', 'max:50'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'audience' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'coaching');
        }

        $validated['benefits'] = array_filter(array_map('trim', explode("\n", (string) ($validated['benefits'] ?? ''))));
        $validated['audience'] = array_filter(array_map('trim', explode("\n", (string) ($validated['audience'] ?? ''))));
        $validated['is_active'] = $request->boolean('is_active', true);

        CoachingService::create($validated);

        return redirect()->route('admin.coaching.index')->with('success', 'Coaching service created.');
    }

    public function edit(CoachingService $coaching): View
    {
        $service = $coaching;

        return view('admin.coaching.form', compact('service'));
    }

    public function update(Request $request, CoachingService $coaching, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:coaching_services,slug,' . $coaching->id],
            'icon' => ['nullable', 'string', 'max:50'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'audience' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'coaching');
        }

        $validated['benefits'] = array_filter(array_map('trim', explode("\n", (string) ($validated['benefits'] ?? ''))));
        $validated['audience'] = array_filter(array_map('trim', explode("\n", (string) ($validated['audience'] ?? ''))));
        $validated['is_active'] = $request->boolean('is_active', false);

        $coaching->update($validated);

        return redirect()->route('admin.coaching.index')->with('success', 'Coaching service updated.');
    }

    public function destroy(CoachingService $coaching): RedirectResponse
    {
        $coaching->delete();

        return redirect()->route('admin.coaching.index')->with('success', 'Coaching service deleted.');
    }
}
