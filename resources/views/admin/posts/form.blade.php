<x-layouts.admin title="Post Form">
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <h1 class="font-display text-3xl font-light text-ink">{{ $post->exists ? 'Edit Article' : 'New Article' }}</h1>
            <a href="{{ route('admin.posts.index') }}" class="text-xs text-slate hover:underline">Back to Articles</a>
        </div>

        <form action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 border border-slate-200 rounded-[2px] space-y-6 shadow-sm">
            @csrf
            @if($post->exists)
                @method('PUT')
            @endif

            <div>
                <label class="form-label">Article Title *</label>
                <input type="text" name="title" required class="form-input" value="{{ old('title', $post->title) }}">
                @error('title')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Category</label>
                <select name="post_category_id" class="form-input">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('post_category_id', $post->post_category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="form-label">Excerpt (Short summary)</label>
                <textarea name="excerpt" rows="2" class="form-input text-sm">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div>
                <label class="form-label">Body Content (HTML Supported) *</label>
                <textarea name="body" rows="12" required class="form-input font-mono text-sm">{{ old('body', $post->body) }}</textarea>
                @error('body')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Published Date</label>
                    <input type="datetime-local" name="published_at" class="form-input text-sm" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="flex items-center pt-6">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }} class="rounded-[2px] text-brand-500">
                        <span>Feature on Homepage</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="form-label">Featured Image</label>
                @if($post->imageUrl())
                    <div class="mb-2 size-24 rounded-[2px] overflow-hidden border border-slate-200">
                        <img src="{{ asset($post->imageUrl()) }}" alt="Preview" class="w-full h-full object-cover">
                    </div>
                @endif
                <input type="file" name="image" class="text-xs">
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline btn-sm">Cancel</a>
                <button type="submit" class="btn btn-primary btn-sm">Save Article</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
