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
            ['email' => 'admin@crossriversministries.org'],
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
            ['email' => 'admin@crossriversministries.org'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'role' => 'super_admin',
            ]
        );

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
            ['email' => 'admin@crossriversministries.org'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
            ]
        );
        $admin->update(['two_factor_enabled' => false]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@crossriversministries.org',
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
            ['email' => 'admin@crossriversministries.org'],
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
            'email' => 'admin@crossriversministries.org',
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
            ['email' => 'admin@crossriversministries.org'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'role' => 'super_admin',
            ]
        );

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
            ['email' => 'admin@crossriversministries.org'],
            [
                'name' => 'Jane Audrey',
                'password' => bcrypt('password123'),
                'is_admin' => true,
                'role' => 'super_admin',
            ]
        );

        $admin->update([
            'two_factor_enabled' => true,
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);

        // 1. Initial login credentials submission redirects to 2FA challenge
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@crossriversministries.org',
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
}
