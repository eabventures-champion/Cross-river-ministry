<x-layouts.admin title="Podcast Episodes">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-light text-ink">Podcast Episodes</h1>
                <p class="text-xs text-slate">Manage your audio teachings, series, and show notes.</p>
            </div>
            <a href="{{ route('admin.podcasts.create') }}" class="btn btn-primary btn-sm flex items-center gap-1.5">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Episode</span>
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Episode Title</th>
                        <th class="p-4">Podcast Show</th>
                        <th class="p-4">Duration</th>
                        <th class="p-4">Air Date</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($episodes as $ep)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-[2px] bg-brand-500 text-white font-bold text-xs flex items-center justify-center overflow-hidden shrink-0">
                                        @if($ep->imageUrl())
                                            <img src="{{ $ep->imageUrl() }}" alt="{{ $ep->title }}" class="size-full object-cover">
                                        @else
                                            <span>EP</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-semibold text-ink block">{{ $ep->title }}</span>
                                        <span class="text-xs text-muted line-clamp-1 max-w-sm">{{ Str::limit($ep->description, 60) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-700">
                                {{ $ep->podcast->title ?? 'Main Show' }}
                            </td>
                            <td class="p-4 text-xs font-mono">
                                {{ $ep->duration ?: '—' }}
                            </td>
                            <td class="p-4 text-xs text-slate-500">
                                {{ $ep->published_at ? $ep->published_at->format('M d, Y') : 'Draft' }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] {{ $ep->is_published ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $ep->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.podcasts.edit', $ep) }}" class="text-brand-500 hover:underline text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.podcasts.destroy', $ep) }}" method="POST" class="inline" onsubmit="return confirm('Delete this podcast episode?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-muted">
                                No podcast episodes uploaded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $episodes->links() }}
    </div>
</x-layouts.admin>
