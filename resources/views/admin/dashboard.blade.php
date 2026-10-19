<x-layouts.admin title="Dashboard">
    <div class="space-y-8">
        <div>
            <h1 class="font-display text-3xl font-light text-ink">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-slate">Here is an overview of Cross Rivers Ministries site activity and content.</p>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-5 border border-slate-200 rounded-[2px]">
                <span class="text-xs font-semibold uppercase text-slate-400 block">Coaching Disciplines</span>
                <span class="font-display text-3xl font-bold text-brand-500 mt-1 block">{{ $stats['coaching_services'] }}</span>
            </div>
            <div class="bg-white p-5 border border-slate-200 rounded-[2px]">
                <span class="text-xs font-semibold uppercase text-slate-400 block">Published Articles</span>
                <span class="font-display text-3xl font-bold text-brand-500 mt-1 block">{{ $stats['posts'] }}</span>
            </div>
            <div class="bg-white p-5 border border-slate-200 rounded-[2px]">
                <span class="text-xs font-semibold uppercase text-slate-400 block">Podcasts & Videos</span>
                <span class="font-display text-3xl font-bold text-brand-500 mt-1 block">{{ $stats['episodes'] + $stats['videos'] }}</span>
            </div>
            <div class="bg-white p-5 border border-slate-200 rounded-[2px]">
                <span class="text-xs font-semibold uppercase text-slate-400 block">Store Orders</span>
                <span class="font-display text-3xl font-bold text-green-600 mt-1 block">{{ $stats['orders'] }}</span>
            </div>
        </div>

        <!-- Recent Contact Submissions -->
        <div class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-display text-xl font-normal text-ink">Recent Inquiries & Contact Submissions</h3>
                <a href="{{ route('admin.inbox.index') }}" class="text-xs font-semibold text-brand-500 hover:underline">View All Inquiries</a>
            </div>

            @if($recentSubmissions->count())
                <div class="divide-y divide-slate-100 text-sm">
                    @foreach($recentSubmissions as $sub)
                        <div class="py-3 flex items-center justify-between">
                            <div>
                                <span class="font-semibold text-ink block">{{ $sub->name }}</span>
                                <span class="text-xs text-muted">{{ $sub->email }} · {{ $sub->subject }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs uppercase font-bold px-2 py-0.5 rounded-[2px] {{ $sub->read_at ? 'bg-slate-100 text-slate-600' : 'bg-green-100 text-green-800' }}">
                                    {{ $sub->read_at ? 'Read' : 'New' }}
                                </span>
                                <span class="text-[0.6875rem] text-muted block mt-1">{{ $sub->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-xs text-muted py-4">No submissions received yet.</p>
            @endif
        </div>
    </div>
</x-layouts.admin>
