<x-layouts.admin :title="$episode->exists ? 'Edit Podcast Episode' : 'Add Podcast Episode'">
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.podcasts.index') }}" class="text-xs text-slate-500 hover:text-ink flex items-center gap-1">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Podcasts</span>
            </a>
        </div>

        <div>
            <h1 class="font-display text-3xl font-light text-ink">{{ $episode->exists ? 'Edit Podcast Episode' : 'Add Podcast Episode' }}</h1>
            <p class="text-xs text-slate">Audio file, episode cover, duration, and detailed transcript/show notes.</p>
        </div>

        <form action="{{ $episode->exists ? route('admin.podcasts.update', $episode) : route('admin.podcasts.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
            @csrf
            @if($episode->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="title">Episode Title *</label>
                    <input type="text" id="title" name="title" required class="form-input" value="{{ old('title', $episode->title) }}" placeholder="e.g. Crossing the River of Fear">
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="podcast_id">Podcast Show *</label>
                    <select id="podcast_id" name="podcast_id" required class="form-input">
                        @foreach($podcasts as $p)
                            <option value="{{ $p->id }}" {{ old('podcast_id', $episode->podcast_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('podcast_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="duration">Duration (e.g. 34:20)</label>
                    <input type="text" id="duration" name="duration" class="form-input" value="{{ old('duration', $episode->duration) }}" placeholder="32:15">
                    @error('duration')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="audio_url">External Audio URL (or upload below)</label>
                    <input type="url" id="audio_url" name="audio_url" class="form-input" value="{{ old('audio_url', $episode->audio_url) }}" placeholder="https://...">
                    @error('audio_url')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label" for="description">Episode Summary</label>
                <textarea id="description" name="description" rows="4" class="form-input" placeholder="What listeners will learn in this session...">{{ old('description', $episode->description) }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label">Episode Cover Art</label>
                    <input type="file" name="image" accept="image/*" class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                    @error('image')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label">Audio File (.mp3)</label>
                    <input type="file" name="audio_file" accept="audio/*" class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                    @error('audio_file')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $episode->exists ? $episode->is_published : true) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">Published (Available in podcast player)</span>
                </label>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.podcasts.index') }}" class="btn btn-secondary px-5">Cancel</a>
                <button type="submit" class="btn btn-primary px-6 py-2.5">
                    <span>{{ $episode->exists ? 'Update Episode' : 'Create Episode' }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
