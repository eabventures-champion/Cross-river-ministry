<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CrossRiversTest extends TestCase
{
    public function test_public_pages_load_successfully(): void
    {
        $routes = [
            '/',
            '/about',
            '/coaching',
            '/coaching/spiritual-coaching',
            '/consulting-speaking',
            '/coaching-advice-line',
            '/podcasts',
            '/videos',
            '/blog',
            '/testimonials',
            '/store',
            '/store/cart',
            '/contact',
            '/sitemap.xml',
            '/admin/login',
            '/admin/forgot-password',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_admin_dashboard_requires_authentication(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
            ]
        );

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
    }

    public function test_all_admin_management_pages_load_successfully(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );
        $admin->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);

        $adminRoutes = [
            route('admin.dashboard'),
            route('admin.profile.edit'),
            route('admin.team.index'),
            route('admin.team.invite'),
            route('admin.coaching.index'),
            route('admin.coaches.index'),
            route('admin.consulting.index'),
            route('admin.testimonials.index'),
            route('admin.posts.index'),
            route('admin.podcasts.index'),
            route('admin.videos.index'),
            route('admin.media.index'),
            route('admin.products.index'),
            route('admin.orders.index'),
            route('admin.inbox.index'),
            route('admin.subscribers.index'),
            route('admin.settings.index', 'general'),
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_admin_login_with_remember_me_sets_cookie(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
            ]
        );
        $admin->update(['two_factor_enabled' => false]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@crossriversministries.com',
            'password' => 'password123',
            'remember' => '1',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        // Verify the remember token is set on user and cookie exists
        $admin->refresh();
        $this->assertNotNull($admin->getRememberToken());
        $response->assertCookie(auth()->guard()->getRecallerName());
    }

    public function test_authenticated_admin_can_view_and_update_profile(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
            ]
        );

        // Can view profile page
        $response = $this->actingAs($admin)->get(route('admin.profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Admin Profile');
        $response->assertSee('Jane Audrey');

        // Can update profile info
        $updateResponse = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Jane Audrey Updated',
            'email' => 'admin@crossriversministries.com',
            'title' => 'Founder & Lead Coach',
            'bio' => 'Serving purpose-driven individuals.',
        ]);

        $updateResponse->assertRedirect(route('admin.profile.edit'));
        $admin->refresh();
        $this->assertEquals('Jane Audrey Updated', $admin->name);
        $this->assertEquals('Founder & Lead Coach', $admin->title);

        // Revert name back to Jane Audrey for consistency
        $admin->update(['name' => 'Jane Audrey', 'role' => 'super_admin']);
    }

    public function test_super_admin_can_invite_member_and_invitee_can_accept(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );
        $admin->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);

        // 1. Can view team list & invite screen
        $response = $this->actingAs($admin)->get(route('admin.team.index'));
        $response->assertStatus(200);
        $response->assertSee('Active Team Members');
        $response->assertSee('Invite New Member');

        // 2. Can create an invitation
        $inviteEmail = 'associate.coach@crossriversministries.org';
        \App\Models\AdminInvitation::where('email', $inviteEmail)->delete();
        User::where('email', $inviteEmail)->delete();

        $inviteResponse = $this->actingAs($admin)->post(route('admin.team.invitations.send'), [
            'email' => $inviteEmail,
            'name' => 'Associate Coach John',
            'role' => 'coach',
            'custom_permissions' => ['manage_coaching' => true],
        ]);

        $inviteResponse->assertRedirect(route('admin.team.index'));

        $invitation = \App\Models\AdminInvitation::where('email', $inviteEmail)->first();
        $this->assertNotNull($invitation);
        $this->assertEquals('coach', $invitation->role);

        // 3. Invitee visits acceptance link
        $acceptPage = $this->get(route('admin.invitations.accept', $invitation->token));
        $acceptPage->assertStatus(200);
        $acceptPage->assertSee('Welcome to the CRM Team');

        // 4. Invitee submits account registration
        $processResponse = $this->post(route('admin.invitations.process', $invitation->token), [
            'name' => 'Coach John Mark',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
        ]);

        $processResponse->assertRedirect(route('admin.dashboard'));

        // 5. Verify created user and authenticated state
        $newUser = User::where('email', $inviteEmail)->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('coach', $newUser->role);
        $this->assertTrue($newUser->isAdmin());
        $this->assertTrue($newUser->hasPermission('manage_coaching'));
        $this->assertFalse($newUser->hasPermission('manage_store'));

        // Cleanup
        $newUser->delete();
        $invitation->delete();
    }

    public function test_admin_login_with_two_factor_authentication(): void
    {
        Mail::fake();

        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );
        $admin->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);

        $admin->update([
            'two_factor_enabled' => true,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);

        // 1. Initial login credentials submission redirects to 2FA challenge
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@crossriversministries.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.two-factor.show'));
        $this->assertGuest();

        $admin->refresh();
        $this->assertNotNull($admin->two_factor_code);
        $this->assertTrue($admin->two_factor_expires_at->isFuture());

        Mail::assertSent(\App\Mail\TwoFactorCodeMail::class, function ($mail) use ($admin) {
            return $mail->user->id === $admin->id && $mail->code === $admin->two_factor_code;
        });

        // 2. 2FA view renders properly
        $twoFactorPage = $this->get(route('admin.two-factor.show'));
        $twoFactorPage->assertStatus(200);
        $twoFactorPage->assertSee('Two-Factor Authentication');
        $twoFactorPage->assertSee('Enter Verification Code');

        // 3. Submitting invalid code fails
        $invalidResponse = $this->post(route('admin.two-factor.verify'), [
            'code' => '000000',
        ]);
        $invalidResponse->assertSessionHasErrors(['code']);
        $this->assertGuest();

        // 4. Submitting valid code logs in user
        $validResponse = $this->post(route('admin.two-factor.verify'), [
            'code' => $admin->two_factor_code,
        ]);

        $validResponse->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);

        // Code should be wiped after use
        $admin->refresh();
        $this->assertNull($admin->two_factor_code);

        // 5. Admin can toggle 2FA off from profile
        $toggleResponse = $this->actingAs($admin)->put(route('admin.profile.two-factor'), [
            'current_password' => 'password123',
        ]);

        $toggleResponse->assertRedirect(route('admin.profile.edit'));
        $admin->refresh();
        $this->assertFalse($admin->two_factor_enabled);
    }

    public function test_admin_forgot_password_and_reset_flow(): void
    {
        Mail::fake();

        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('oldpassword123'),
                'is_admin' => true,
                'role' => 'super_admin',
            ]
        );

        // 1. Forgot password form renders properly
        $response = $this->get(route('admin.password.request'));
        $response->assertStatus(200);
        $response->assertSee('Reset Password');
        $response->assertSee('Administrator Email Address');

        // 2. Requesting reset link dispatches reset email
        $postResponse = $this->post(route('admin.password.email'), [
            'email' => 'admin@crossriversministries.com',
        ]);
        $postResponse->assertSessionHas('status');

        Mail::assertSent(\App\Mail\AdminResetPasswordMail::class, function ($mail) use ($admin) {
            return $mail->user->id === $admin->id && ! empty($mail->resetUrl);
        });

        // 3. Reset password page loads with token
        $token = \Illuminate\Support\Facades\Password::broker()->createToken($admin);
        $resetPage = $this->get(route('admin.password.reset', ['token' => $token, 'email' => $admin->email]));
        $resetPage->assertStatus(200);
        $resetPage->assertSee('Set New Password');

        // 4. Submitting password reset changes password and redirects to login
        $updateResponse = $this->post(route('admin.password.update'), [
            'token' => $token,
            'email' => $admin->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $updateResponse->assertRedirect(route('admin.login'));
        $updateResponse->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $admin->password));

        // 5. Login form now shows updated password
        $loginPage = $this->get(route('admin.login'));
        $loginPage->assertStatus(200);
        $loginPage->assertSee('value="newpassword123"', false);
    }

    public function test_login_form_displays_updated_credentials_when_changed_in_profile(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );
        $admin->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);

        // Update profile email
        $newEmail = 'updated.admin@crossriversministries.com';
        User::where('email', $newEmail)->delete();
        $updateEmailResponse = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Jane Audrey',
            'email' => $newEmail,
        ]);
        $updateEmailResponse->assertRedirect(route('admin.profile.edit'));

        // Update password
        $admin->password = bcrypt('password123');
        $admin->save();

        $newPassword = 'myNewSecretPass456!';
        $updatePassResponse = $this->actingAs($admin)->put(route('admin.profile.password'), [
            'current_password' => 'password123',
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);
        $updatePassResponse->assertRedirect(route('admin.profile.edit'));
        $updatePassResponse->assertSessionHas('success');

        auth()->logout();

        // Check login form
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('value="' . $newEmail . '"', false);
        $response->assertSee('value="' . $newPassword . '"', false);

        // Reset back for subsequent test clean state
        $admin->refresh();
        $admin->update([
            'email' => 'admin@crossriversministries.com',
            'password' => bcrypt('password123'),
        ]);
        \App\Models\Setting::updateOrCreate(
            ['key' => 'auth.admin_email'],
            ['group' => 'auth', 'value' => 'admin@crossriversministries.com']
        );
        \App\Models\Setting::updateOrCreate(
            ['key' => 'auth.admin_password'],
            ['group' => 'auth', 'value' => 'password123']
        );
    }

    public function test_admin_can_view_delete_all_products_caution_and_delete_all(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );
        $admin->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);

        // Ensure at least one product exists
        \App\Models\Product::firstOrCreate(
            ['slug' => 'test-guide-for-deletion'],
            [
                'name' => 'Test Guide for Deletion',
                'price' => 19.99,
                'stock' => 10,
                'is_active' => true,
            ]
        );

        $this->assertGreaterThan(0, \App\Models\Product::count());

        // 1. Admin can view products page with "Delete All Products" button and Caution modal
        $indexResponse = $this->actingAs($admin)->get(route('admin.products.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Delete All Products');
        $indexResponse->assertSee('Caution: Delete All Store Products?');
        $indexResponse->assertSee('Proceed to Delete All Products');

        // 2. Admin can delete all products
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.products.destroy-all'));
        $deleteResponse->assertRedirect(route('admin.products.index'));
        $deleteResponse->assertSessionHas('success');

        $this->assertEquals(0, \App\Models\Product::count());

        // 3. Deleting again when empty gracefully informs admin
        $emptyDeleteResponse = $this->actingAs($admin)->delete(route('admin.products.destroy-all'));
        $emptyDeleteResponse->assertRedirect(route('admin.products.index'));
        $emptyDeleteResponse->assertSessionHas('info');
    }

    public function test_admin_can_dynamically_input_new_category_when_creating_product(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );
        $admin->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);

        $customCatName = 'Dynamic Apparel ' . rand(100, 999);
        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Custom Dynamic Tee ' . rand(100, 999),
            'new_category' => $customCatName,
            'price' => 29.99,
            'stock' => 25,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_categories', [
            'name' => $customCatName,
        ]);
        $createdCat = \App\Models\ProductCategory::where('name', $customCatName)->first();
        $this->assertNotNull($createdCat);

        $this->assertDatabaseHas('products', [
            'product_category_id' => $createdCat->id,
            'price' => 29.99,
        ]);
    }

    public function test_admin_can_quick_store_category_via_endpoint(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );
        $admin->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);

        $quickName = 'Quick Category ' . rand(100, 999);
        $response = $this->actingAs($admin)->postJson(route('admin.products.categories.quick-store'), [
            'name' => $quickName,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'category' => [
                'name' => $quickName,
            ],
        ]);

        $this->assertDatabaseHas('product_categories', ['name' => $quickName]);
    }

    public function test_admin_can_import_products_from_exported_file(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );
        $admin->update([
            'is_admin' => true,
            'is_active' => true,
            'role' => 'super_admin',
        ]);

        // Post import request
        $response = $this->actingAs($admin)->post(route('admin.products.import'));
        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_categories', ['name' => 'TEES']);
        $this->assertDatabaseHas('product_categories', ['name' => 'MUGS']);
        $this->assertGreaterThanOrEqual(100, \App\Models\Product::count());

        $teeProduct = \App\Models\Product::whereHas('category', fn ($q) => $q->where('name', 'TEES'))->first();
        $this->assertNotNull($teeProduct);
        $this->assertEquals(24.99, (float) $teeProduct->price);

        $mugProduct = \App\Models\Product::whereHas('category', fn ($q) => $q->where('name', 'MUGS'))->first();
        $this->assertNotNull($mugProduct);
        $this->assertEquals(9.99, (float) $mugProduct->price);
    }

    public function test_product_sku_generation_and_inventory_stock_tracking(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@crossriversministries.com'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'is_active' => true,
                'role' => 'super_admin',
            ]
        );

        // 1. Create product without SKU or stock -> defaults to 50 stock and auto-generates SKU
        $cat = \App\Models\ProductCategory::firstOrCreate(['name' => 'TEES'], ['slug' => 'tees']);
        $product = \App\Models\Product::create([
            'product_category_id' => $cat->id,
            'name' => 'Peace In The River Daily Tee',
            'price' => 24.99,
        ]);

        $this->assertNotEmpty($product->sku);
        $this->assertStringContainsString('CRM-', $product->sku);
        $this->assertEquals(50, $product->stock);
        $this->assertEquals(50, $product->initial_stock);
        $this->assertTrue($product->in_stock);
        $this->assertEquals(50, $product->ever_stocked);
        $this->assertEquals(0, $product->total_purchased);

        // 2. Low stock alert triggering
        $product->update(['stock' => 5]);
        $this->assertTrue($product->isLowStock());

        // 3. Admin products index displays metrics and low stock notification
        $response = $this->actingAs($admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Low Stock Inventory Notification');
        $response->assertSee('Available Stock');
        $response->assertSee('Ever Stocked');
        $response->assertSee('Total Purchased');
        $response->assertSee($product->sku);

        // Clean up
        $product->delete();
    }
}
