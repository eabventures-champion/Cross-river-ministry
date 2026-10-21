<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TeamInvitationMail;
use App\Models\AdminInvitation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        $users = User::where('is_admin', true)
            ->orderByRaw("CASE role WHEN 'super_admin' THEN 1 WHEN 'admin' THEN 2 WHEN 'editor' THEN 3 WHEN 'coach' THEN 4 ELSE 5 END")
            ->orderBy('name')
            ->get();

        $invitations = AdminInvitation::whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->get();

        return view('admin.team.index', [
            'users' => $users,
            'invitations' => $invitations,
            'roles' => User::ROLES,
            'permissions' => User::PERMISSIONS,
        ]);
    }

    public function createInvitation(): View
    {
        return view('admin.team.invite', [
            'roles' => User::ROLES,
            'permissions' => User::PERMISSIONS,
        ]);
    }

    public function sendInvitation(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'name' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'custom_permissions' => ['nullable', 'array'],
        ]);

        // Security check: Only super_admin can invite other super_admins
        if ($request->role === 'super_admin' && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'Only super administrators can assign the Super Administrator role.');
        }

        // Invalidate any previous pending invitations for this email
        AdminInvitation::where('email', $request->email)->delete();

        $invitation = AdminInvitation::create([
            'email' => $request->email,
            'name' => $request->name,
            'role' => $request->role,
            'custom_permissions' => $request->custom_permissions ?? [],
            'token' => Str::random(48),
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);

        $mailSent = false;
        try {
            Mail::to($invitation->email)->send(new TeamInvitationMail($invitation));
            $mailSent = true;
        } catch (\Throwable $e) {
            Log::error('Failed to send team invitation email: ' . $e->getMessage());
        }

        $message = $mailSent
            ? "Invitation sent via email to {$invitation->email}! Direct link also available: " . $invitation->getInvitationUrl()
            : "Invitation created for {$invitation->email}. (Email delivery issue logged). Direct link: " . $invitation->getInvitationUrl();

        return redirect()->route('admin.team.index')->with('success', $message);
    }

    public function revokeInvitation(AdminInvitation $invitation): RedirectResponse
    {
        $invitation->delete();
        return redirect()->route('admin.team.index')->with('success', 'Invitation has been revoked.');
    }

    public function resendInvitation(AdminInvitation $invitation): RedirectResponse
    {
        $invitation->update([
            'token' => Str::random(48),
            'expires_at' => now()->addDays(7),
        ]);

        $mailSent = false;
        try {
            Mail::to($invitation->email)->send(new TeamInvitationMail($invitation));
            $mailSent = true;
        } catch (\Throwable $e) {
            Log::error('Failed to re-send team invitation email: ' . $e->getMessage());
        }

        $message = $mailSent
            ? "Invitation email re-sent to {$invitation->email} with fresh 7-day validity."
            : "Invitation renewed for {$invitation->email}. Direct link: " . $invitation->getInvitationUrl();

        return redirect()->route('admin.team.index')->with('success', $message);
    }

    public function editUser(User $user): View
    {
        return view('admin.team.edit', [
            'user' => $user,
            'roles' => User::ROLES,
            'permissions' => User::PERMISSIONS,
        ]);
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'title' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'custom_permissions' => ['nullable', 'array'],
        ]);

        // Protect primary admin or last super admin
        if ($user->email === 'admin@crossriversministries.org' && $request->role !== 'super_admin') {
            return back()->with('error', 'The primary founding administrator role cannot be downgraded.');
        }

        $user->update([
            'role' => $request->role,
            'title' => $request->title,
            'is_active' => $request->boolean('is_active', true),
            'custom_permissions' => $request->custom_permissions ?? [],
        ]);

        return redirect()->route('admin.team.index')->with('success', "Team member {$user->name}'s permissions have been updated.");
    }

    public function toggleUserStatus(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->email === 'admin@crossriversministries.org') {
            return back()->with('error', 'The primary ministry founder account cannot be deactivated.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'activated' : 'suspended';
        return redirect()->route('admin.team.index')->with('success', "Account for {$user->name} has been {$status}.");
    }

    public function deleteUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot remove your own administrator account.');
        }

        if ($user->email === 'admin@crossriversministries.org') {
            return back()->with('error', 'The founding administrator cannot be removed.');
        }

        $user->delete();

        return redirect()->route('admin.team.index')->with('success', "Team member removed from Cross Rivers Ministries admin team.");
    }

    // Public / Invitee endpoints
    public function showAcceptInvitation(string $token): View
    {
        $invitation = AdminInvitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return view('admin.team.invite-expired', ['invitation' => $invitation]);
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('admin.login')->with('info', 'This invitation has already been accepted. Please sign in with your password.');
        }

        return view('admin.team.accept', [
            'invitation' => $invitation,
            'roleDetails' => User::ROLES[$invitation->role] ?? null,
        ]);
    }

    public function processAcceptInvitation(Request $request, string $token): RedirectResponse
    {
        $invitation = AdminInvitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired() || $invitation->isAccepted()) {
            return redirect()->route('admin.login')->with('error', 'This invitation is no longer valid.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'role' => $invitation->role,
            'custom_permissions' => $invitation->custom_permissions ?? [],
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
            'last_login_at' => now(),
        ]);

        $invitation->update([
            'accepted_at' => now(),
        ]);

        auth()->login($user);

        return redirect()->route('admin.dashboard')->with('success', "Welcome to the Cross Rivers Ministries administration team, {$user->name}!");
    }
}
