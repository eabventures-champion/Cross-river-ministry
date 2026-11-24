<?php

use App\Models\Setting;
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

    Setting::updateOrCreate(
        ['key' => 'auth.admin_email'],
        ['group' => 'auth', 'value' => $email]
    );
    Setting::updateOrCreate(
        ['key' => 'auth.admin_password'],
        ['group' => 'auth', 'value' => $password]
    );

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

Artisan::command('products:import {file?}', function (?string $file = null) {
    $filePath = $file ?: public_path('exports/exported_products.json');
    if (! file_exists($filePath)) {
        $filePath = base_path('exported_products.json');
    }

    if (! file_exists($filePath)) {
        $this->error("Exported products file not found at: {$filePath}");
        return 1;
    }

    $content = file_get_contents($filePath);
    $data = json_decode($content, true);
    if (! is_array($data)) {
        $this->error("Invalid JSON content in {$filePath}");
        return 1;
    }

    $this->info("Importing " . count($data) . " products from {$filePath}...");

    $count = 0;
    foreach ($data as $item) {
        if (empty($item['name'])) {
            continue;
        }

        $catName = trim($item['category'] ?? 'General');
        $category = \App\Models\ProductCategory::firstOrCreate(
            ['name' => $catName],
            ['slug' => \Illuminate\Support\Str::slug($catName)]
        );

        $slug = ! empty($item['slug']) ? \Illuminate\Support\Str::slug($item['slug']) : \Illuminate\Support\Str::slug($item['name']);

        \App\Models\Product::updateOrCreate(
            ['slug' => $slug],
            [
                'product_category_id' => $category->id,
                'name' => $item['name'],
                'price' => isset($item['price']) ? (float) $item['price'] : 0.00,
                'stock' => isset($item['stock']) ? (int) $item['stock'] : 50,
                'image' => $item['full_image_url'] ?? $item['image_url'] ?? null,
                'is_active' => true,
            ]
        );
        $count++;
    }

    $this->info("Successfully imported {$count} products!");
})->purpose('Import store products from JSON export file');

