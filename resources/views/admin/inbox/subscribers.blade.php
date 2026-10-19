<x-layouts.admin title="Newsletter Subscribers">
    <div class="space-y-6">
        <div>
            <h1 class="font-display text-3xl font-light text-ink">Newsletter Subscribers</h1>
            <p class="text-xs text-slate">Subscribers to the Cross Rivers Ministries Quarterly Newsletter.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm max-w-3xl">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Email</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Source</th>
                        <th class="p-4">Subscribed At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subscribers as $sub)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-semibold text-ink">{{ $sub->email }}</td>
                            <td class="p-4 text-xs">{{ $sub->name ?? '—' }}</td>
                            <td class="p-4 text-xs font-mono">{{ $sub->source ?? 'website' }}</td>
                            <td class="p-4 text-xs font-mono">{{ $sub->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-xs text-slate-400">No subscribers yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $subscribers->links() }}
    </div>
</x-layouts.admin>
