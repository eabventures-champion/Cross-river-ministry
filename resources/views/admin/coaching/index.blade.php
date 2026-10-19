<x-layouts.admin title="Coaching Services">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-light text-ink">Coaching Services</h1>
                <p class="text-xs text-slate">Manage your core coaching disciplines and curriculum details.</p>
            </div>
            <a href="{{ route('admin.coaching.create') }}" class="btn btn-primary btn-sm">Add New Service</a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Title</th>
                        <th class="p-4">Tagline</th>
                        <th class="p-4">Order</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($services as $s)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-semibold text-ink">{{ $s->title }}</td>
                            <td class="p-4 text-xs">{{ $s->tagline }}</td>
                            <td class="p-4 text-xs font-mono">{{ $s->sort_order }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-xs font-bold rounded-[2px] {{ $s->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $s->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.coaching.edit', $s) }}" class="text-brand-500 hover:underline text-xs">Edit</a>
                                <form action="{{ route('admin.coaching.destroy', $s) }}" method="POST" class="inline" onsubmit="return confirm('Delete this coaching track?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $services->links() }}
    </div>
</x-layouts.admin>
