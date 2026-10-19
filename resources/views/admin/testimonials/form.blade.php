<x-layouts.admin :title="$testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial'">
    <div class="max-w-3xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.testimonials.index') }}" class="text-xs text-slate-500 hover:text-ink flex items-center gap-1">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Testimonials</span>
            </a>
        </div>

        <div>
            <h1 class="font-display text-3xl font-light text-ink">{{ $testimonial->exists ? 'Edit Testimonial' : 'Add Testimonial' }}</h1>
            <p class="text-xs text-slate">Client story, headline breakthrough, location, and client endorsement.</p>
        </div>

        <form action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
              method="POST"
              enctype="multipart/form-data"
              class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
            @csrf
            @if($testimonial->exists)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="name">Client / Author Name *</label>
                    <input type="text" id="name" name="name" required class="form-input" value="{{ old('name', $testimonial->name) }}" placeholder="e.g. K. R., P. K., or Sarah Jenkins">
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="location">Location / Subtitle</label>
                    <input type="text" id="location" name="location" class="form-input" value="{{ old('location', $testimonial->location) }}" placeholder="e.g. Maryland or Richmond, VA">
                    @error('location')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="form-label" for="category">Category</label>
                    <input type="text" id="category" name="category" list="category-options" class="form-input" value="{{ old('category', $testimonial->category) }}" placeholder="e.g. Spiritual & Life Purpose">
                    <datalist id="category-options">
                        <option value="Spiritual & Life Purpose">
                        <option value="Emotional & Personal Support">
                        <option value="Faith & Trust">
                        <option value="Spiritual Mentorship">
                        <option value="Family Dynamics">
                        <option value="Work-Life Balance">
                        <option value="General">
                    </datalist>
                    @error('category')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="sort_order">Sort Order</label>
                    <input type="number" id="sort_order" name="sort_order" class="form-input" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}">
                    @error('sort_order')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="form-label" for="headline">Headline / Key Takeaway</label>
                <input type="text" id="headline" name="headline" class="form-input" value="{{ old('headline', $testimonial->headline) }}" placeholder="e.g. Talking to Ms. Jane is like talking to a friend — multiple breakthroughs">
                @error('headline')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label" for="content">Full Testimonial Story / Endorsement *</label>
                <textarea id="content" name="content" rows="6" required class="form-input" placeholder="Client testimony in their own words...">{{ old('content', $testimonial->content) }}</textarea>
                @error('content')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="form-label">Client Photo</label>
                <div class="flex items-center gap-4 mt-2">
                    @if($testimonial->imageUrl())
                        <div class="size-14 rounded-full overflow-hidden shrink-0 border border-slate-200">
                            <img src="{{ $testimonial->imageUrl() }}" alt="{{ $testimonial->name }}" class="size-full object-cover">
                        </div>
                    @endif
                    <input type="file" name="photo" accept="image/*" class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                </div>
                @error('photo')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-6 pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $testimonial->is_featured) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">Feature on Homepage</span>
                </label>

                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', $testimonial->exists ? $testimonial->is_published : true) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                    <span class="text-xs font-medium text-slate">Published (Visible publicly)</span>
                </label>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary px-5">Cancel</a>
                <button type="submit" class="btn btn-primary px-6 py-2.5">
                    <span>{{ $testimonial->exists ? 'Update Testimonial' : 'Create Testimonial' }}</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
