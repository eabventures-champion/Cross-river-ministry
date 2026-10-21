<x-layouts.admin title="Consulting & Speaking">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="font-display text-2xl sm:text-3xl font-light text-ink">Consulting & Speaking Engagements</h1>
                <p class="text-xs text-slate">Manage your corporate advisory programs, speaking topics, and executive workshops.</p>
            </div>
            <a href="{{ route('admin.consulting.create') }}" class="btn btn-primary btn-sm flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Consulting Track</span>
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-x-auto shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Title / Program</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Audience</th>
                        <th class="p-4">Order</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($services as $s)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4">
                                <span class="font-semibold text-ink block">{{ $s->title }}</span>
                                <span class="text-xs text-muted line-clamp-1 max-w-sm">{{ Str::limit($s->summary, 70) }}</span>
                            </td>
                            <td class="p-4">
                                <span class="text-[0.6875rem] px-2 py-0.5 rounded-[2px] font-semibold uppercase {{ $s->type === 'speaking' ? 'bg-amber-100 text-amber-800' : ($s->type === 'workshop' ? 'bg-purple-100 text-purple-800' : 'bg-brand-100 text-brand-800') }}">
                                    {{ $s->type }}
                                </span>
                            </td>
                            <td class="p-4 text-xs text-slate-600">{{ $s->target_audience ?: '—' }}</td>
                            <td class="p-4 text-xs font-mono">{{ $s->sort_order }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] {{ $s->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $s->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.consulting.edit', $s) }}" class="text-brand-500 hover:underline text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.consulting.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Delete this consulting track?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-muted">
                                No consulting or speaking programs created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $services->links() }}
    </div>
</x-layouts.admin>
