<x-layouts.admin :title="$service->exists ? 'Edit Consulting Track' : 'Add Consulting Track'">
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.consulting.index') }}" class="text-xs text-slate-500 hover:text-ink flex items-center gap-1">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Consulting</span>
            </a>
        </div>

        <div>
            <h1 class="font-display text-3xl font-light text-ink">{{ $service->exists ? 'Edit Consulting Track' : 'Add Consulting Track' }}</h1>
            <p class="text-xs text-slate">Configure track details, description, outcomes, and promotional media.</p>
        </div>

        <form action="{{ $service->exists ? route('admin.consulting.update', $service) : route('admin.consulting.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
            @csrf
            @if($service->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="title">Program Title *</label>
                    <input type="text" id="title" name="title" required class="form-input" value="{{ old('title', $service->title) }}" placeholder="e.g. Executive Leadership Coaching">
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="type">Service Type *</label>
                    <select id="type" name="type" required class="form-input">
                        <option value="consulting" {{ old('type', $service->type) === 'consulting' ? 'selected' : '' }}>Corporate Consulting</option>
                        <option value="speaking" {{ old('type', $service->type) === 'speaking' ? 'selected' : '' }}>Keynote / Speaking</option>
                        <option value="workshop" {{ old('type', $service->type) === 'workshop' ? 'selected' : '' }}>Leadership Workshop</option>
                    </select>
                    @error('type')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="target_audience">Target Audience</label>
                    <input type="text" id="target_audience" name="target_audience" class="form-input" value="{{ old('target_audience', $service->target_audience) }}" placeholder="e.g. Executives, Pastoral Staff, Teams">
                    @error('target_audience')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="sort_order">Display Order</label>
                    <input type="number" id="sort_order" name="sort_order" class="form-input" value="{{ old('sort_order', $service->sort_order ?? 0) }}">
                    @error('sort_order')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label" for="summary">Short Summary</label>
                <textarea id="summary" name="summary" rows="3" class="form-input" placeholder="One or two sentences highlighting this offering...">{{ old('summary', $service->summary) }}</textarea>
                @error('summary')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label" for="description">Full Description</label>
                <textarea id="description" name="description" rows="5" class="form-input" placeholder="Detailed program curriculum, format, delivery structure...">{{ old('description', $service->description) }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Featured Image</label>
                <div class="flex items-center gap-4 mt-2">
                    @if($service->imageUrl())
                        <div class="size-16 rounded-[2px] overflow-hidden shrink-0 border border-slate-200">
                            <img src="{{ $service->imageUrl() }}" alt="{{ $service->title }}" class="size-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                </div>
                @error('image')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $service->exists ? $service->is_active : true) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">Active (Displayed on Consulting & Speaking page)</span>
                </label>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.consulting.index') }}" class="btn btn-secondary px-5">Cancel</a>
                <button type="submit" class="btn btn-primary px-6 py-2.5">
                    <span>{{ $service->exists ? 'Update Program' : 'Create Program' }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
