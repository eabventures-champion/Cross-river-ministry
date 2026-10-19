<x-layouts.admin title="Coaching Service Form">
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <h1 class="font-display text-3xl font-light text-ink">{{ $service->exists ? 'Edit Coaching Service' : 'New Coaching Service' }}</h1>
            <a href="{{ route('admin.coaching.index') }}" class="text-xs text-slate hover:underline">Back to List</a>
        </div>

        <form action="{{ $service->exists ? route('admin.coaching.update', $service) : route('admin.coaching.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 border border-slate-200 rounded-[2px] space-y-6 shadow-sm">
            @csrf
            @if($service->exists)
                @method('PUT')
            @endif

            <div>
                <label class="form-label">Service Title *</label>
                <input type="text" name="title" required class="form-input" value="{{ old('title', $service->title) }}">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Tagline</label>
                <input type="text" name="tagline" class="form-input" value="{{ old('tagline', $service->tagline) }}">
            </div>

            <div>
                <label class="form-label">Short Excerpt (Summary)</label>
                <textarea name="excerpt" rows="3" class="form-input text-sm">{{ old('excerpt', $service->excerpt) }}</textarea>
            </div>

            <div>
                <label class="form-label">Full Description (HTML Supported)</label>
                <textarea name="description" rows="8" class="form-input font-mono text-sm">{{ old('description', $service->description) }}</textarea>
            </div>

            <div>
                <label class="form-label">Benefits (One per line)</label>
                <textarea name="benefits" rows="4" class="form-input font-mono text-sm" placeholder="One benefit per line">{{ old('benefits', is_array($service->benefits) ? implode("\n", $service->benefits) : '') }}</textarea>
            </div>

            <div>
                <label class="form-label">Audience (One per line)</label>
                <textarea name="audience" rows="4" class="form-input font-mono text-sm" placeholder="One target audience item per line">{{ old('audience', is_array($service->audience) ? implode("\n", $service->audience) : '') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
                </div>
                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }} class="rounded-[2px] text-brand-500">
                        <span>Is Active / Visible</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="form-label">Cover Image</label>
                @if($service->imageUrl())
                    <div class="mb-2 size-24 rounded-[2px] overflow-hidden border border-slate-200">
                        <img src="{{ asset($service->imageUrl()) }}" alt="Current image" class="w-full h-full object-cover">
                    </div>
                @endif
                <input type="file" name="image" class="text-xs">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.coaching.index') }}" class="btn btn-outline btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm">Save Service</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
