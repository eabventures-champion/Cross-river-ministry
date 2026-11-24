<x-layouts.admin title="Dashboard">
    <div class="space-y-6 sm:space-y-8">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-light text-ink">Welcome back, {{ auth()->user()->name }}</h1>
            <p class="text-xs text-slate mt-0.5">Here is an overview of Cross Rivers Ministries site activity and content.</p>
        </div>

        @if(($stats['low_stock_count'] ?? 0) > 0 || ($stats['out_of_stock_count'] ?? 0) > 0)
            <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-[2px] shadow-xs flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="size-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-xs font-bold text-amber-900 uppercase tracking-wide">Inventory Notice:</span>
                        <span class="text-xs text-amber-800 ml-1">
                            {{ $stats['low_stock_count'] }} product(s) running low (&le; 10 units)
                            @if(($stats['out_of_stock_count'] ?? 0) > 0)
                                · <strong class="text-red-700">{{ $stats['out_of_stock_count'] }} out of stock</strong>
                            @endif
                        </span>
                    </div>
                </div>
                <a href="{{ route('admin.products.index', ['inventory_filter' => 'low_stock']) }}" class="btn btn-secondary btn-sm py-1 px-3 text-xs shrink-0">
                    Review Stock
                </a>
            </div>
        @endif

        <!-- Metrics Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            <div class="bg-white p-4 sm:p-5 border border-slate-200 rounded-[2px] shadow-xs">
                <span class="text-[0.6875rem] sm:text-xs font-semibold uppercase text-slate-400 block truncate">Coaching Disciplines</span>
                <span class="font-display text-2xl sm:text-3xl font-bold text-brand-500 mt-1 block">{{ $stats['coaching_services'] }}</span>
            </div>
            <div class="bg-white p-4 sm:p-5 border border-slate-200 rounded-[2px] shadow-xs">
                <span class="text-[0.6875rem] sm:text-xs font-semibold uppercase text-slate-400 block truncate">Published Articles</span>
                <span class="font-display text-2xl sm:text-3xl font-bold text-brand-500 mt-1 block">{{ $stats['posts'] }}</span>
            </div>
            <div class="bg-white p-4 sm:p-5 border border-slate-200 rounded-[2px] shadow-xs">
                <span class="text-[0.6875rem] sm:text-xs font-semibold uppercase text-slate-400 block truncate">Podcasts & Videos</span>
                <span class="font-display text-2xl sm:text-3xl font-bold text-brand-500 mt-1 block">{{ $stats['episodes'] + $stats['videos'] }}</span>
            </div>
            <div class="bg-white p-4 sm:p-5 border border-slate-200 rounded-[2px] shadow-xs">
                <span class="text-[0.6875rem] sm:text-xs font-semibold uppercase text-slate-400 block truncate">Store Orders</span>
                <span class="font-display text-2xl sm:text-3xl font-bold text-green-600 mt-1 block">{{ $stats['orders'] }}</span>
            </div>
        </div>

        <!-- Recent Contact Submissions -->
        <div class="bg-white border border-slate-200 rounded-[2px] p-4 sm:p-6 space-y-4 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 gap-2">
                <h3 class="font-display text-lg sm:text-xl font-normal text-ink">Recent Inquiries & Contact Submissions</h3>
                <a href="{{ route('admin.inbox.index') }}" class="text-xs font-semibold text-brand-500 hover:underline shrink-0">View All</a>
            </div>

            @if($recentSubmissions->count())
                <div class="divide-y divide-slate-100 text-sm">
                    @foreach($recentSubmissions as $sub)
                        <div class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div class="min-w-0">
                                <span class="font-semibold text-ink block truncate">{{ $sub->name }}</span>
                                <span class="text-xs text-muted block truncate">{{ $sub->email }} · {{ $sub->subject }}</span>
                            </div>
                            <div class="flex items-center sm:flex-col sm:items-end justify-between sm:justify-start gap-2 shrink-0">
                                <span class="text-[0.625rem] uppercase font-bold px-2 py-0.5 rounded-[2px] {{ $sub->read_at ? 'bg-slate-100 text-slate-600' : 'bg-green-100 text-green-800' }}">
                                    {{ $sub->read_at ? 'Read' : 'New' }}
                                </span>
                                <span class="text-[0.6875rem] text-muted">{{ $sub->created_at->diffForHumans() }}</span>
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
