<x-layouts.admin title="Team & Permissions">
    <div class="space-y-8">
        <!-- Header & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-display text-3xl font-light text-ink">Team & Role Permissions</h1>
                <p class="text-xs text-slate">Invite staff, associate coaches, and editors with granular access to ministry operations.</p>
            </div>
            <a href="{{ route('admin.team.invite') }}" class="btn btn-primary self-start sm:self-auto flex items-center gap-2">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.765z" />
                </svg>
                <span>Invite New Member</span>
            </a>
        </div>

        <!-- Role Highlights Cards (Premium UX) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @foreach($roles as $key => $role)
                <div class="bg-white border border-slate-200 rounded-[2px] p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $role['label'] }}</span>
                            <span class="inline-block size-2 rounded-full {{ $key === 'super_admin' ? 'bg-purple-500' : ($key === 'admin' ? 'bg-brand-500' : ($key === 'editor' ? 'bg-green-500' : 'bg-amber-500')) }}"></span>
                        </div>
                        <p class="text-[0.75rem] text-slate-600 leading-relaxed">{{ $role['description'] }}</p>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-muted">
                        <span>Active:</span>
                        <strong class="text-ink font-semibold">{{ $users->where('role', $key)->count() }}</strong>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pending Invitations Section -->
        @if($invitations->count())
            <div class="bg-white border border-amber-200 rounded-[2px] p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-amber-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex size-2 rounded-full bg-amber-500 animate-pulse"></span>
                        <h2 class="font-display text-lg font-medium text-ink">Pending Invitations ({{ $invitations->count() }})</h2>
                    </div>
                    <span class="text-[0.6875rem] text-slate-500">Invitations expire after 7 days</span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($invitations as $invite)
                        <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-sm">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-ink">{{ $invite->name ?: 'Invited Member' }}</span>
                                    <span class="text-xs text-slate-500">({{ $invite->email }})</span>
                                    <span class="text-[0.6875rem] px-2 py-0.5 rounded-[2px] font-semibold border {{ $roles[$invite->role]['badge'] ?? 'bg-slate-100 text-slate-800' }}">
                                        {{ $roles[$invite->role]['label'] ?? $invite->role }}
                                    </span>
                                </div>
                                <div class="text-xs text-muted mt-1 flex items-center gap-2">
                                    <span>Invited by {{ $invite->inviter->name ?? 'System' }}</span>
                                    <span>·</span>
                                    <span>Expires {{ $invite->expires_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2" x-data="{ copied: false, url: '{{ $invite->getInvitationUrl() }}' }">
                                <button type="button"
                                        @click="navigator.clipboard.writeText(url); copied = true; setTimeout(() => copied = false, 2500)"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-[2px] flex items-center gap-1.5 transition-colors">
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                                    </svg>
                                    <span x-text="copied ? 'Copied Link!' : 'Copy Link'"></span>
                                </button>

                                <form action="{{ route('admin.team.invitations.resend', $invite) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-medium rounded-[2px] transition-colors">
                                        Renew
                                    </button>
                                </form>

                                <form action="{{ route('admin.team.invitations.revoke', $invite) }}" method="POST" onsubmit="return confirm('Revoke this invitation link?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 text-red-600 hover:text-red-800 text-xs font-medium transition-colors">
                                        Revoke
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Active Administrators & Staff -->
        <div class="bg-white border border-slate-200 rounded-[2px] p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="font-display text-xl font-medium text-ink">Active Team Members ({{ $users->count() }})</h2>
                <span class="text-xs text-muted">All members authorized to access the CRM Admin Portal</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead>
                        <tr class="border-b border-slate-200 text-[0.6875rem] uppercase font-semibold text-slate-400 tracking-wider">
                            <th class="py-3 px-3">Member</th>
                            <th class="py-3 px-3">Role</th>
                            <th class="py-3 px-3">Permissions Scope</th>
                            <th class="py-3 px-3">Last Active</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 px-3">
                                    <div class="flex items-center gap-3">
                                        <div class="size-9 rounded-full bg-brand-500 text-white font-semibold text-sm flex items-center justify-center overflow-hidden shrink-0">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="size-full object-cover">
                                            @else
                                                <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-ink">{{ $user->name }}</span>
                                                @if($user->id === auth()->id())
                                                    <span class="text-[0.625rem] bg-slate-100 text-slate-600 font-bold px-1.5 py-0.2 rounded-[2px]">YOU</span>
                                                @endif
                                            </div>
                                            <span class="text-xs text-muted block">{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-3">
                                    <span class="inline-block text-[0.6875rem] px-2.5 py-0.5 rounded-[2px] font-semibold border {{ $roles[$user->role]['badge'] ?? 'bg-slate-100 text-slate-800' }}">
                                        {{ $roles[$user->role]['label'] ?? ucfirst($user->role) }}
                                    </span>
                                    @if($user->title)
                                        <span class="text-[0.6875rem] text-slate-400 block mt-0.5">{{ $user->title }}</span>
                                    @endif
                                </td>

                                <td class="py-4 px-3">
                                    @if($user->isSuperAdmin())
                                        <span class="text-xs text-purple-700 font-medium flex items-center gap-1">
                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                            Full Global Access
                                        </span>
                                    @else
                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                            @foreach($permissions as $permKey => $permLabel)
                                                @if($user->hasPermission($permKey))
                                                    <span class="text-[0.625rem] bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded-[2px]" title="{{ $permLabel }}">
                                                        {{ explode(' ', $permLabel)[0] }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                <td class="py-4 px-3 text-xs text-slate-500">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </td>

                                <td class="py-4 px-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium {{ $user->is_active ? 'text-green-700' : 'text-red-600' }}">
                                        <span class="size-1.5 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $user->is_active ? 'Active' : 'Suspended' }}
                                    </span>
                                </td>

                                <td class="py-4 px-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('admin.team.edit', $user) }}" class="text-xs font-semibold text-brand-500 hover:text-brand-700">
                                            Edit
                                        </a>

                                        @if($user->id !== auth()->id() && $user->email !== 'admin@crossriversministries.org')
                                            <span class="text-slate-200">|</span>
                                            <form action="{{ route('admin.team.status', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs font-medium {{ $user->is_active ? 'text-amber-600 hover:text-amber-700' : 'text-green-600 hover:text-green-700' }}">
                                                    {{ $user->is_active ? 'Suspend' : 'Activate' }}
                                                </button>
                                            </form>

                                            <span class="text-slate-200">|</span>
                                            <form action="{{ route('admin.team.delete', $user) }}" method="POST" onsubmit="return confirm('Remove {{ $user->name }} from the admin team? This action is permanent.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700">
                                                    Remove
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
