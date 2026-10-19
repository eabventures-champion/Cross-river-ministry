<x-layouts.admin title="Invite Team Member">
    <div class="max-w-3xl space-y-8">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.team.index') }}" class="text-xs text-slate-500 hover:text-ink flex items-center gap-1">
                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                <span>Back to Team</span>
            </a>
        </div>

        <div>
            <h1 class="font-display text-3xl font-light text-ink">Invite New Team Member</h1>
            <p class="text-xs text-slate">Generate a secure 7-day invitation link to grant access to the Cross Rivers Ministries dashboard.</p>
        </div>

        <form action="{{ route('admin.team.invitations.send') }}" method="POST" class="space-y-6" x-data="{ selectedRole: '{{ old('role', 'admin') }}', customScope: false }">
            @csrf

            <!-- Member Details Card -->
            <div class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
                <h2 class="font-display text-lg font-medium text-ink border-b border-slate-100 pb-3">Invitee Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label" for="email">Work / Ministry Email</label>
                        <input type="email" id="email" name="email" required placeholder="colleague@crossriversministries.org" class="form-input" value="{{ old('email') }}">
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="name">Full Name (Optional)</label>
                        <input type="text" id="name" name="name" placeholder="Pastor / Coach Name" class="form-input" value="{{ old('name') }}">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Role Selection Card -->
            <div class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-5">
                <h2 class="font-display text-lg font-medium text-ink border-b border-slate-100 pb-3">Assign Ministry Role</h2>

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

            <!-- Granular Permissions Customization -->
            <div class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-4" x-show="selectedRole !== 'super_admin'">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="font-display text-lg font-medium text-ink">Fine-tune Permissions (Optional)</h2>
                        <p class="text-xs text-slate">Override the default role bundle with granular access permissions.</p>
                    </div>
                    <label class="inline-flex items-center gap-2 text-xs cursor-pointer">
                        <input type="checkbox" x-model="customScope" class="text-brand-500 rounded-[2px]">
                        <span class="font-medium text-slate">Customize Permissions</span>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2" x-show="customScope">
                    @foreach($permissions as $permKey => $permLabel)
                        <label class="flex items-center gap-2.5 p-3 bg-slate-50 border border-slate-200 rounded-[2px] text-xs text-slate-700 cursor-pointer hover:bg-slate-100 transition-colors">
                            <input type="checkbox" name="custom_permissions[{{ $permKey }}]" value="1" class="text-brand-500 rounded-[2px]">
                            <span class="font-medium">{{ $permLabel }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('admin.team.index') }}" class="btn btn-secondary px-6">Cancel</a>
                <button type="submit" class="btn btn-primary px-8 py-3">
                    <span>Generate & Send Invitation</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
