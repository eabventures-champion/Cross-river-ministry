<x-layouts.admin title="Administrator Profile">
    <div class="max-w-4xl space-y-8">
        <div>
            <h1 class="font-display text-3xl font-light text-ink">Admin Profile</h1>
            <p class="text-xs text-slate">Manage your administrator account details, profile picture, and security credentials.</p>
        </div>

        <!-- Profile Information Form -->
        <div class="bg-white border border-slate-200 rounded-[2px] p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="font-display text-xl font-medium text-ink">Personal Information</h2>
                <p class="text-xs text-slate mt-0.5">Update your display name, title, contact email, and bio.</p>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Avatar Preview & Upload -->
                <div>
                    <label class="form-label">Profile Photo</label>
                    <div class="flex items-center gap-5 mt-2">
                        <div class="size-16 rounded-full bg-brand-500 text-white font-display text-2xl font-bold flex items-center justify-center overflow-hidden shrink-0 border-2 border-white shadow-sm ring-1 ring-slate-200">
                            @if($user->avatar)
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="size-full object-cover">
                            @else
                                <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <input type="file" name="avatar" accept="image/*" class="text-xs text-slate file:mr-3 file:py-2 file:px-3 file:rounded-[2px] file:border-0 file:text-xs file:font-semibold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100 cursor-pointer">
                            <p class="text-[0.6875rem] text-muted mt-1">PNG, JPG, or WEBP up to 4MB.</p>
                        </div>
                    </div>
                    @error('avatar')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name" required class="form-input" value="{{ old('name', $user->name) }}">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" required class="form-input" value="{{ old('email', $user->email) }}">
                        @error('email')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="form-label" for="title">Title / Role</label>
                    <input type="text" id="title" name="title" class="form-input" placeholder="e.g. Founder & Lead Coach" value="{{ old('title', $user->title) }}">
                    @error('title')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="form-label" for="bio">Bio / Summary</label>
                    <textarea id="bio" name="bio" rows="4" class="form-input" placeholder="Brief biographical introduction...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn btn-primary px-6 py-2.5">
                        <span>Save Changes</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Security / Change Password -->
        <div class="bg-white border border-slate-200 rounded-[2px] p-6 sm:p-8 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <h2 class="font-display text-xl font-medium text-ink">Change Password</h2>
                <p class="text-xs text-slate mt-0.5">Ensure your account uses a long, secure passphrase.</p>
            </div>

            <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-5" x-data="{ showCurrent: false, showNew: false }">
                @csrf
                @method('PUT')

                <div>
                    <label class="form-label" for="current_password">Current Password</label>
                    <div class="relative mt-1">
                        <input :type="showCurrent ? 'text' : 'password'"
                               type="password"
                               id="current_password"
                               name="current_password"
                               required
                               autocomplete="current-password"
                               class="form-input pr-12">
                        <button type="button"
                                @click="showCurrent = !showCurrent"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate/70 hover:text-ink focus:outline-none">
                            <svg x-show="!showCurrent" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showCurrent" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('current_password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="form-label" for="password">New Password</label>
                        <div class="relative mt-1">
                            <input :type="showNew ? 'text' : 'password'"
                                   type="password"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                                   class="form-input pr-12">
                            <button type="button"
                                    @click="showNew = !showNew"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate/70 hover:text-ink focus:outline-none">
                                <svg x-show="!showNew" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showNew" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="password_confirmation">Confirm New Password</label>
                        <div class="relative mt-1">
                            <input :type="showNew ? 'text' : 'password'"
                                   type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                                   class="form-input">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn btn-primary px-6 py-2.5">
                        <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>
