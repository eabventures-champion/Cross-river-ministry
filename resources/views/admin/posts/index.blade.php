<x-layouts.admin title="Blog Posts">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-light text-ink">Blog Articles</h1>
                <p class="text-xs text-slate">Manage your spiritual reflections and ministry articles.</p>
            </div>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">Write New Article</a>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] overflow-hidden shadow-sm">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                    <tr>
                        <th class="p-4">Title</th>
                        <th class="p-4">Category</th>
                        <th class="p-4">Author</th>
                        <th class="p-4">Published Date</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($posts as $p)
                        <tr class="hover:bg-slate-50">
                            <td class="p-4 font-semibold text-ink">{{ $p->title }}</td>
                            <td class="p-4 text-xs">{{ $p->category?->name ?? 'Uncategorized' }}</td>
                            <td class="p-4 text-xs">{{ $p->author?->name ?? 'Jane Audrey' }}</td>
                            <td class="p-4 text-xs font-mono">{{ $p->published_at?->format('M d, Y') ?? 'Draft' }}</td>
                            <td class="p-4 text-right space-x-2">
                                <a href="{{ route('admin.posts.edit', $p) }}" class="text-brand-500 hover:underline text-xs">Edit</a>
                                <form action="{{ route('admin.posts.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Delete this article?')">
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
        {{ $posts->links() }}
    </div>
</x-layouts.admin>
