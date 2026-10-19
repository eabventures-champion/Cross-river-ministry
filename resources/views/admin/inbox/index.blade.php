<x-layouts.admin title="Inquiries & Messages">
    <div class="space-y-6">
        <div>
            <h1 class="font-display text-3xl font-light text-ink">Inquiries & Contact Submissions</h1>
            <p class="text-xs text-slate">Submissions from the general contact form and consulting/speaking requests.</p>
        </div>

        <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
            <a href="{{ route('admin.inbox.index') }}" class="px-3 py-1.5 text-xs font-semibold rounded-[2px] {{ !request('type') ? 'bg-brand-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">All Messages</a>
            <a href="{{ route('admin.inbox.index', ['type' => 'contact']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-[2px] {{ request('type') === 'contact' ? 'bg-brand-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">Contact Form</a>
            <a href="{{ route('admin.inbox.index', ['type' => 'consulting']) }}" class="px-3 py-1.5 text-xs font-semibold rounded-[2px] {{ request('type') === 'consulting' ? 'bg-brand-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">Speaking / Consulting</a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Sender</th>
                        <th class="p-4">Type</th>
                        <th class="p-4">Subject</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($submissions as $sub)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4">
                                <span class="font-semibold text-ink block">{{ $sub->name }}</span>
                                <span class="text-xs text-muted">{{ $sub->email }}</span>
                            </td>
                            <td class="p-4">
                                <span class="text-xs uppercase font-mono px-2 py-0.5 rounded-[2px] {{ $sub->type === 'consulting' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $sub->type }}
                                </span>
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-800">{{ $sub->subject }}</td>
                            <td class="p-4 text-xs font-mono">{{ $sub->created_at->format('M d, Y') }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-xs font-bold rounded-[2px] {{ $sub->read_at ? 'bg-slate-100 text-slate-500' : 'bg-green-100 text-green-800' }}">
                                    {{ $sub->read_at ? 'Read' : 'New' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.inbox.show', $sub) }}" class="text-brand-500 hover:underline text-xs">View</a>
                                <form action="{{ route('admin.inbox.destroy', $sub) }}" method="POST" class="inline" onsubmit="return confirm('Delete message?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-slate-400">No submissions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $submissions->links() }}
    </div>
</x-layouts.admin>
