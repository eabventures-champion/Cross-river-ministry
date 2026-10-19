<x-layouts.admin title="Coaches & Staff">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-light text-ink">Coaches & Staff</h1>
                <p class="text-xs text-slate">Manage your ministry coaches and leadership profiles displayed on the public website.</p>
            </div>
            <a href="{{ route('admin.coaches.create') }}" class="btn btn-primary btn-sm flex items-center gap-1.5">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Coach / Staff</span>
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Coach Profile</th>
                        <th class="p-4">Title / Role</th>
                        <th class="p-4">Contact</th>
                        <th class="p-4">Order</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($coaches as $coach)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="size-10 rounded-full bg-brand-500 text-white font-semibold text-xs flex items-center justify-center overflow-hidden shrink-0 border border-slate-200">
                                        @if($coach->imageUrl())
                                            <img src="{{ $coach->imageUrl() }}" alt="{{ $coach->name }}" class="size-full object-cover">
                                        @else
                                            <span>{{ $coach->initials() }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="font-semibold text-ink block">{{ $coach->name }}</span>
                                        <span class="text-xs text-muted line-clamp-1 max-w-xs">{{ Str::limit($coach->bio, 60) }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-xs font-medium text-slate-700">
                                {{ $coach->role ?: '—' }}
                            </td>
                            <td class="p-4 text-xs text-muted">
                                {{ $coach->email ?: '—' }}
                            </td>
                            <td class="p-4 text-xs font-mono">
                                {{ $coach->sort_order }}
                            </td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 text-[0.6875rem] font-bold rounded-[2px] {{ $coach->is_active ? 'bg-green-100 text-green-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $coach->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.coaches.edit', $coach) }}" class="text-brand-500 hover:underline text-xs font-medium">Edit</a>
                                <form action="{{ route('admin.coaches.destroy', $coach) }}" method="POST" class="inline" onsubmit="return confirm('Delete this coach profile?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline text-xs font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-xs text-muted">
                                No coaches or staff members created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $coaches->links() }}
    </div>
</x-layouts.admin>
