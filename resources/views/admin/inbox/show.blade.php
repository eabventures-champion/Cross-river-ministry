<x-layouts.admin title="Inquiry from {{ $submission->name }}">
    <div class="space-y-6 max-w-3xl">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.inbox.index') }}" class="text-xs text-slate hover:underline">← Back to Inbox</a>
            <span class="text-xs uppercase font-mono px-2 py-0.5 rounded-[2px] bg-slate-200">
                Type: {{ $submission->type }}
            </span>
        </div>

        <div class="bg-white p-8 border border-slate-200 rounded-[2px] shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-4 flex justify-between items-start">
                <div>
                    <h1 class="font-display text-2xl font-normal text-ink">{{ $submission->subject }}</h1>
                    <div class="text-xs text-muted mt-1 space-x-2">
                        <span>From: <strong>{{ $submission->name }}</strong> ({{ $submission->email }})</span>
                        @if($submission->phone)
                            <span>· Phone: {{ $submission->phone }}</span>
                        @endif
                        <span>· Received: {{ $submission->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            @if(!empty($submission->meta))
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-[2px] space-y-1 text-xs">
                    <span class="font-bold text-slate-500 uppercase block mb-1">Additional Submission Data</span>
                    @foreach($submission->meta as $metaKey => $metaVal)
                        <div>
                            <span class="text-muted capitalize">{{ str_replace('_', ' ', $metaKey) }}:</span>
                            <span class="font-medium text-slate-800">{{ $metaVal }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="space-y-2">
                <span class="text-xs font-semibold uppercase tracking-wider text-muted block">Message Body:</span>
                <p class="text-sm text-slate-800 leading-relaxed whitespace-pre-line bg-paper p-6 border border-line rounded-[2px]">
                    {{ $submission->message }}
                </p>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="mailto:{{ $submission->email }}?subject=Re: {{ urlencode($submission->subject) }}" class="btn btn-primary btn-sm">
                    <span>Reply via Email</span>
                </a>

                <form action="{{ route('admin.inbox.destroy', $submission) }}" method="POST" onsubmit="return confirm('Delete message?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:underline text-xs">Delete Submission</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
