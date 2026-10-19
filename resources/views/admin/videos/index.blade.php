<x-layouts.admin title="Video Library">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-light text-ink">Video Library</h1>
                <p class="text-xs text-slate">Manage video sermons, video coaching sessions, and masterclass streams.</p>
            </div>
            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary btn-sm flex items-center gap-1.5">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Video</span>
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Video</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Duration</th>
                        <th class="p-4">Featured</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($videos as $v)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-16 h-10 bg-slate-800 rounded-[2px] overflow-hidden shrink-0 flex items-center justify-center text-white text-[0.625rem] font-bold">
                                        @if($v->thumbnailUrl())
                                            <img src="{{ $v->thumbnailUrl() }}" alt="{{ $v->title }}" class="size-full object-cover">
                                        @else
                                            <span>VIDEO</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-semibold text-ink block">{{ $v->title }}</span>
                                        <span class="text-xs text-muted line-clamp-1 max-w-sm">{{ Str::limit($v->description, 60) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-700">
                                {{ $v->category->name ?? 'General' }}
                            </td>
                            <td class="p-4 text-xs font-mono">
                                {{ $v->duration ?: '—' }}
                            </td>
                            <td class="p-4">
                                @if($v->is_featured)
                                    <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] bg-amber-100 text-amber-800">Featured</span>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] {{ $v->is_published ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $v->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.videos.edit', $v) }}" class="text-brand-500 hover:underline text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.videos.destroy', $v) }}" method="POST" class="inline" onsubmit="return confirm('Delete this video?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-muted">
                                No videos added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $videos->links() }}
    </div>
</x-layouts.admin>
