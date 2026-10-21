<x-layouts.admin title="Settings — {{ $currentGroup['label'] }}">
    <div class="space-y-6 max-w-4xl">
        <div>
            <h1 class="font-display text-3xl font-light text-ink">{{ $currentGroup['label'] }}</h1>
            <p class="text-xs text-slate">{{ $currentGroup['description'] }}</p>
        </div>

        <!-- Group Selector Tabs -->
        <div class="flex items-center gap-1.5 sm:gap-2 border-b border-slate-200 pb-2 overflow-x-auto no-scrollbar py-1">
            @foreach($groups as $k => $grp)
                <a href="{{ route('admin.settings.index', $k) }}" class="px-3.5 sm:px-4 py-2 text-xs font-semibold uppercase tracking-wider rounded-[2px] shrink-0 whitespace-nowrap {{ $currentGroupKey === $k ? 'bg-brand-500 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    {{ $grp['label'] }}
                </a>
            @endforeach
        </div>

        <!-- Settings Form -->
        <form action="{{ route('admin.settings.update', $currentGroupKey) }}" method="POST" enctype="multipart/form-data" class="bg-white p-4 sm:p-8 border border-slate-200 rounded-[2px] shadow-sm space-y-6">
            @csrf

            @foreach($currentGroup['fields'] as $fieldKey => $config)
                @php
                    $val = $settings->get("{$currentGroupKey}.{$fieldKey}");
                @endphp
                <div class="space-y-2">
                    <label class="form-label">{{ $config['label'] }}</label>

                    @if($config['type'] === 'textarea')
                        <textarea name="{{ $fieldKey }}" rows="4" class="form-input text-sm">{{ old($fieldKey, $val) }}</textarea>
                    @elseif($config['type'] === 'richtext')
                        <textarea name="{{ $fieldKey }}" rows="8" class="form-input text-sm font-mono">{{ old($fieldKey, $val) }}</textarea>
                    @elseif($config['type'] === 'lines')
                        <textarea name="{{ $fieldKey }}" rows="6" class="form-input text-sm font-mono" placeholder="One item per line (use 'Title | Description')">{{ old($fieldKey, $val) }}</textarea>
                    @elseif($config['type'] === 'image')
                        @if($val)
                            <div class="mb-2 size-24 rounded-[2px] overflow-hidden border border-slate-200">
                                <img src="{{ asset(img_url($val)) }}" alt="Preview" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <input type="file" name="{{ $fieldKey }}" class="text-xs">
                    @else
                        <input type="{{ $config['type'] }}" name="{{ $fieldKey }}" value="{{ old($fieldKey, $val) }}" class="form-input text-sm">
                    @endif
                </div>
            @endforeach

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</x-layouts.admin>
