<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultingService;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultingServiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = ConsultingService::query();

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        $services = $query->orderBy('sort_order')->paginate(15)->withQueryString();

        return view('admin.consulting.index', compact('services'));
    }

    public function create(): View
    {
        $service = new ConsultingService();

        return view('admin.consulting.form', compact('service'));
    }

    public function store(Request $request, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:consulting,speaking,workshop'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:consulting_services,slug'],
            'icon' => ['nullable', 'string', 'max:50'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'points' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'consulting');
        }

        $validated['points'] = array_filter(array_map('trim', explode("\n", (string) ($validated['points'] ?? ''))));
        $validated['is_active'] = $request->boolean('is_active', true);

        ConsultingService::create($validated);

        return redirect()->route('admin.consulting.index')->with('success', 'Item created successfully.');
    }

    public function edit(ConsultingService $consulting): View
    {
        $service = $consulting;

        return view('admin.consulting.form', compact('service'));
    }

    public function update(Request $request, ConsultingService $consulting, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:consulting,speaking,workshop'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:consulting_services,slug,' . $consulting->id],
            'icon' => ['nullable', 'string', 'max:50'],
            'excerpt' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'points' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $media->storePath($request->file('image'), 'consulting');
        }

        $validated['points'] = array_filter(array_map('trim', explode("\n", (string) ($validated['points'] ?? ''))));
        $validated['is_active'] = $request->boolean('is_active', false);

        $consulting->update($validated);

        return redirect()->route('admin.consulting.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(ConsultingService $consulting): RedirectResponse
    {
        $consulting->delete();

        return redirect()->route('admin.consulting.index')->with('success', 'Item deleted.');
    }
}
