<x-layouts.admin title="Client Testimonials">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-light text-ink">Client Testimonials</h1>
                <p class="text-xs text-slate">Manage life-coaching stories and client endorsements displayed across the website.</p>
            </div>
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Testimonial</span>
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-x-auto shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Client / Author</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Headline & Content</th>
                        <th class="p-4">Featured</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($testimonials as $t)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-9 rounded-full bg-brand-500 text-white font-semibold text-xs flex items-center justify-center overflow-hidden shrink-0">
                                        @if($t->imageUrl())
                                            <img src="{{ $t->imageUrl() }}" alt="{{ $t->name }}" class="size-full object-cover">
                                        @else
                                            <span>{{ $t->initials() ?: strtoupper(substr($t->name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-semibold text-ink block">{{ $t->name }}</span>
                                        <span class="text-xs text-muted">{{ $t->location ?: 'Coaching Client' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-700">
                                {{ $t->category ?: 'General' }}
                            </td>
                            <td class="p-4 text-xs text-slate-600 max-w-md">
                                @if($t->headline)
                                    <span class="font-semibold text-ink block mb-0.5">{{ Str::limit($t->headline, 70) }}</span>
                                @endif
                                <span class="line-clamp-2 text-slate-500">“{{ Str::limit($t->content, 110) }}”</span>
                            </td>
                            <td class="p-4">
                                @if($t->is_featured)
                                    <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] bg-amber-100 text-amber-800">
                                        Featured
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] {{ $t->is_published ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $t->is_published ? 'Published' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.testimonials.edit', $t) }}" class="text-brand-500 hover:underline text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Delete this testimonial?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-muted">
                                No testimonials created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $testimonials->links() }}
    </div>
</x-layouts.admin>
