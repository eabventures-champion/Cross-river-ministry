<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:reset-user {email=admin@crossriversministries.com} {password=password123} {--enable-2fa}', function () {
    $email = strtolower(trim((string) $this->argument('email')));
    $password = (string) $this->argument('password');
    $enable2fa = (bool) $this->option('enable-2fa');

    $user = User::where('email', $email)->first();

    if (! $user) {
        $legacyEmail = str_replace('@crossriversministries.com', '@crossriversministries.org', $email);
        $user = User::where('email', $legacyEmail)->first();
    }

    if (! $user) {
        $user = new User([
            'name' => 'Jane Audrey',
            'title' => 'Founder & Lead Spiritual Life Coach',
        ]);
    }

    $user->name = $user->name ?: 'Jane Audrey';
    $user->email = $email;
    $user->password = Hash::make($password);
    $user->is_admin = true;
    $user->is_active = true;
    $user->role = 'super_admin';
    $user->two_factor_enabled = $enable2fa;
    $user->two_factor_code = null;
    $user->two_factor_expires_at = null;
    $user->save();

    $this->newLine();
    $this->info('====================================================');
    $this->info('  ADMINISTRATOR ACCOUNT READY FOR DIRECT LOGIN');
    $this->info('====================================================');
    $this->line("  Email:    <comment>{$user->email}</comment>");
    $this->line("  Password: <comment>{$password}</comment>");
    $this->line("  Role:     <comment>{$user->role}</comment>");
    $this->line("  2FA:      <comment>" . ($enable2fa ? 'Enabled' : 'Disabled (Direct Login - No email code required)') . '</comment>');
    $this->info('====================================================');
    $this->line('  Sign in directly at: /admin/login');
    $this->newLine();
})->purpose('Create or reset administrator credentials and optionally toggle 2FA');

