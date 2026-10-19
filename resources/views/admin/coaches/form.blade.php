<x-layouts.admin :title="$coach->exists ? 'Edit Coach Profile' : 'Add Coach Profile'">
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.coaches.index') }}" class="text-xs text-slate-500 hover:text-ink flex items-center gap-1">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Coaches</span>
            </a>
        </div>

        <div>
            <h1 class="font-display text-3xl font-light text-ink">{{ $coach->exists ? 'Edit Coach Profile' : 'Add Coach Profile' }}</h1>
            <p class="text-xs text-slate">Provide coach credentials, biographical summary, and photography.</p>
        </div>

        <form action="{{ $coach->exists ? route('admin.coaches.update', $coach) : route('admin.coaches.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
            @csrf
            @if($coach->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required class="form-input" value="{{ old('name', $coach->name) }}" placeholder="e.g. Jane Audrey">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="role">Title / Role</label>
                    <input type="text" id="role" name="role" class="form-input" value="{{ old('role', $coach->role) }}" placeholder="e.g. Founder & Lead Coach">
                    @error('role')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="email">Contact Email</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $coach->email) }}" placeholder="coach@crossriversministries.org">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="sort_order">Display Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" class="form-input" value="{{ old('sort_order', $coach->sort_order ?? 0) }}">
                    @error('sort_order')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label" for="bio">Biographical Summary</label>
                <textarea id="bio" name="bio" rows="4" class="form-input" placeholder="Background, calling, and ministry focus...">{{ old('bio', $coach->bio) }}</textarea>
                @error('bio')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Profile Photo</label>
                <div class="flex items-center gap-4 mt-2">
                    @if($coach->imageUrl())
                        <div class="size-16 rounded-full overflow-hidden shrink-0 border border-slate-200">
                            <img src="{{ $coach->imageUrl() }}" alt="{{ $coach->name }}" class="size-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                </div>
                @error('photo')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coach->exists ? $coach->is_active : true) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">Active (Visible on public About & Coaching pages)</span>
                </label>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.coaches.index') }}" class="btn btn-secondary px-5">Cancel</a>
                <button type="submit" class="btn btn-primary px-6 py-2.5">
                    <span>{{ $coach->exists ? 'Update Coach' : 'Create Coach' }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
