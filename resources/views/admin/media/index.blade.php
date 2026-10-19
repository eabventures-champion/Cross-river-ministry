<x-layouts.admin title="Media Library">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-display text-3xl font-light text-ink">Media Library</h1>
                <p class="text-xs text-slate">Browse uploaded photography, ministry assets, audio files, and documents.</p>
            </div>
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" required class="text-xs text-slate file:mr-2 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                <button type="submit" class="btn btn-primary btn-sm">Upload File</button>
            </form>
        </div>

        <div class="bg-white border border-slate-200 rounded-[2px] p-6 shadow-sm">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @forelse($media as $m)
                    <div class="border border-slate-200 rounded-[2px] p-2 flex flex-col justify-between group hover:border-brand-500 transition-colors">
                        <div class="aspect-square bg-slate-100 rounded-[2px] overflow-hidden flex items-center justify-center">
                            @if(str_starts_with($m->mime_type ?? '', 'image'))
                                <img src="{{ $m->url() }}" alt="{{ $m->filename }}" class="size-full object-cover">
                            @else
                                <span class="text-xs font-bold text-slate-500 uppercase">{{ pathinfo($m->filename, PATHINFO_EXTENSION) }}</span>
                            @endif
                        </div>
                        <div class="mt-2">
                            <span class="text-xs font-medium text-ink block truncate" title="{{ $m->filename }}">{{ $m->filename }}</span>
                            <span class="text-[0.625rem] text-muted">{{ number_format($m->size / 1024, 1) }} KB</span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-xs">
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $m->url() }}'); alert('Image URL copied!')" class="text-brand-500 hover:underline text-[0.6875rem]">Copy URL</button>
                            <form action="{{ route('admin.media.destroy', $m) }}" method="POST" onsubmit="return confirm('Delete this media file?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-[0.6875rem]">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-xs text-muted">
                        No media files uploaded yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $media->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>
