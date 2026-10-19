<x-layouts.admin title="Edit Team Member">
    <div class="max-w-3xl space-y-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.team.index') }}" class="text-xs text-slate-500 hover:text-ink flex items-center gap-1">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Team</span>
            </a>
        </div>

        <div>
            <h1 class="font-display text-3xl font-light text-ink">Edit Member: {{ $user->name }}</h1>
            <p class="text-xs text-slate">Update ministry role assignments and individual system permissions.</p>
        </div>

        <form action="{{ route('admin.team.update', $user) }}" method="POST" class="space-y-6" x-data="{ selectedRole: '{{ old('role', $user->role) }}', customScope: {{ !empty($user->custom_permissions) ? 'true' : 'false' }} }">
            @csrf
            @method('PUT')

            <!-- User Info Summary -->
            <div class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-4">
                <div class="flex items-center gap-4">
                    <div class="size-14 rounded-full bg-brand-500 text-white font-bold text-xl flex items-center justify-center overflow-hidden shrink-0">
                        @if($user->avatar)
                            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="size-full object-cover">
                        @else
                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <h2 class="font-display text-lg font-medium text-ink">{{ $user->name }}</h2>
                        <p class="text-xs text-muted">{{ $user->email }} · Member since {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <label class="form-label" for="title">Title / Ministry Role</label>
                    <input type="text" id="title" name="title" class="form-input" value="{{ old('title', $user->title) }}" placeholder="e.g. Lead Counselor, Editorial Director">
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                        <span class="text-xs font-medium text-slate">Account is active (can access CRM Admin)</span>
                    </label>
                </div>
            </div>

            <!-- Role Selection -->
            <div class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
                <h2 class="font-display text-lg font-medium text-ink border-b border-slate-100 pb-3">Ministry Role</h2>

                <div class="space-y-3">
                    @foreach($roles as $key => $role)
                        @if($key !== 'super_admin' || auth()->user()->isSuperAdmin())
                            <label class="flex items-start gap-3 p-4 border rounded-[2px] cursor-pointer transition-colors"
                                   :class="selectedRole === '{{ $key }}' ? 'border-brand-500 bg-brand-50/40 ring-1 ring-brand-500' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="role" value="{{ $key }}" x-model="selectedRole" class="mt-1 text-brand-500 focus:ring-brand-500">
                                <div class="flex-grow">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-sm text-ink">{{ $role['label'] }}</span>
                                        <span class="text-[0.625rem] px-2 py-0.5 rounded-[2px] font-semibold border {{ $role['badge'] }}">{{ strtoupper($key) }}</span>
                                    </div>
                                    <p class="text-xs text-slate-600 mt-1">{{ $role['description'] }}</p>
                                </div>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Custom Permissions Override -->
            <div class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-4" x-show="selectedRole !== 'super_admin'">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="font-display text-lg font-medium text-ink">Fine-tune Permissions (Optional)</h2>
                        <p class="text-xs text-slate">Override the role bundle with explicit module permissions.</p>
                    </div>
                    <label class="inline-flex items-center gap-2 text-xs cursor-pointer">
                        <input type="checkbox" x-model="customScope" class="text-brand-500 rounded-[2px]">
                        <span class="font-medium text-slate">Customize Permissions</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2" x-show="customScope">
                    @foreach($permissions as $permKey => $permLabel)
                        @php
                            $checked = isset($user->custom_permissions[$permKey])
                                ? (bool)$user->custom_permissions[$permKey]
                                : $user->hasPermission($permKey);
                        @endphp
                        <label class="flex items-center gap-2.5 p-3 bg-slate-50 border border-slate-200 rounded-[2px] text-xs text-slate-700 cursor-pointer hover:bg-slate-100 transition-colors">
                            <input type="checkbox" name="custom_permissions[{{ $permKey }}]" value="1" {{ $checked ? 'checked' : '' }} class="text-brand-500 rounded-[2px]">
                            <span class="font-medium">{{ $permLabel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.team.index') }}" class="btn btn-secondary px-6">Cancel</a>
                <button type="submit" class="btn btn-primary px-8 py-3">
                    <span>Save Changes</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
