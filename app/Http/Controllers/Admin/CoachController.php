<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Services\MediaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CoachController extends Controller
{
    public function index(): View
    {
        $coaches = Coach::orderBy('sort_order')->paginate(15);

        return view('admin.coaches.index', compact('coaches'));
    }

    public function create(): View
    {
        $coach = new Coach();

        return view('admin.coaches.form', compact('coach'));
    }

    public function store(Request $request, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $media->storePath($request->file('photo'), 'coaches');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Coach::create($validated);

        return redirect()->route('admin.coaches.index')->with('success', 'Coach created.');
    }

    public function edit(Coach $coach): View
    {
        return view('admin.coaches.form', compact('coach'));
    }

    public function update(Request $request, Coach $coach, MediaService $media): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'email' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $media->storePath($request->file('photo'), 'coaches');
        }

        $validated['is_active'] = $request->boolean('is_active', false);

        $coach->update($validated);

        return redirect()->route('admin.coaches.index')->with('success', 'Coach updated.');
    }

    public function destroy(Coach $coach): RedirectResponse
    {
        $coach->delete();

        return redirect()->route('admin.coaches.index')->with('success', 'Coach deleted.');
    }
}
