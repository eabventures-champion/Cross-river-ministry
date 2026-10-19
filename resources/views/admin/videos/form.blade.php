<x-layouts.admin :title="$video->exists ? 'Edit Video' : 'Add Video'">
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.videos.index') }}" class="text-xs text-slate-500 hover:text-ink flex items-center gap-1">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Videos</span>
            </a>
        </div>

        <div>
            <h1 class="font-display text-3xl font-light text-ink">{{ $video->exists ? 'Edit Video' : 'Add Video' }}</h1>
            <p class="text-xs text-slate">YouTube embed ID, category assignment, thumbnail cover, and summary.</p>
        </div>

        <form action="{{ $video->exists ? route('admin.videos.update', $video) : route('admin.videos.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
            @csrf
            @if($video->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="title">Video Title *</label>
                    <input type="text" id="title" name="title" required class="form-input" value="{{ old('title', $video->title) }}" placeholder="e.g. Navigating Career Transitions">
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="category_id">Category</label>
                    <select id="category_id" name="category_id" class="form-input">
                        <option value="">Select Category</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id', $video->category_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="video_url">YouTube / Vimeo URL or ID *</label>
                    <input type="text" id="video_url" name="video_url" required class="form-input" value="{{ old('video_url', $video->video_url) }}" placeholder="https://youtube.com/watch?v=... or ID">
                    @error('video_url')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="duration">Duration (e.g. 18:45)</label>
                    <input type="text" id="duration" name="duration" class="form-input" value="{{ old('duration', $video->duration) }}" placeholder="15:30">
                    @error('duration')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label" for="description">Video Description</label>
                <textarea id="description" name="description" rows="4" class="form-input" placeholder="Summary and key takeaways...">{{ old('description', $video->description) }}</textarea>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Custom Thumbnail Cover</label>
                <div class="flex items-center gap-4 mt-2">
                    @if($video->thumbnailUrl())
                        <div class="w-20 h-12 rounded-[2px] overflow-hidden shrink-0 border border-slate-200">
                            <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}" class="size-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                </div>
                @error('thumbnail')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-6 pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $video->is_featured) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">Feature on Video Library hero</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $video->exists ? $video->is_published : true) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">Published (Visible publicly)</span>
                </label>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.videos.index') }}" class="btn btn-secondary px-5">Cancel</a>
                <button type="submit" class="btn btn-primary px-6 py-2.5">
                    <span>{{ $video->exists ? 'Update Video' : 'Add Video' }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
