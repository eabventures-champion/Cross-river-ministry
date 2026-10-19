<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CoachController as AdminCoachController;
use App\Http\Controllers\Admin\CoachingServiceController as AdminCoachingServiceController;
use App\Http\Controllers\Admin\ConsultingServiceController as AdminConsultingServiceController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InboxController as AdminInboxController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PodcastController as AdminPodcastController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\AdviceLineController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\ConsultingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PodcastController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');
Route::get('/about', AboutController::class)->name('about');

// Coaching Services
Route::prefix('coaching')->name('coaching.')->group(function () {
    Route::get('/', [CoachingController::class, 'index'])->name('index');
    Route::get('/{service}', [CoachingController::class, 'show'])->name('show');
});

// Consulting & Speaking
Route::get('/consulting-speaking', [ConsultingController::class, 'index'])->name('consulting');
Route::post('/consulting-speaking', [ConsultingController::class, 'store'])->name('consulting.store');

// Advice Line
Route::get('/coaching-advice-line', AdviceLineController::class)->name('advice-line');

// Media Libraries
Route::prefix('podcasts')->name('podcasts.')->group(function () {
    Route::get('/', [PodcastController::class, 'index'])->name('index');
    Route::get('/{episode}', [PodcastController::class, 'show'])->name('show');
});

Route::prefix('videos')->name('videos.')->group(function () {
    Route::get('/', [VideoController::class, 'index'])->name('index');
    Route::get('/{video}', [VideoController::class, 'show'])->name('show');
});

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{post}', [BlogController::class, 'show'])->name('show');
});

// Testimonials
Route::get('/testimonials', TestimonialController::class)->name('testimonials');

// Store & Cart
Route::prefix('store')->name('store.')->group(function () {
    Route::get('/', [StoreController::class, 'index'])->name('index');
    Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{product}', [StoreController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{product}', [StoreController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove/{product}', [StoreController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [StoreController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [StoreController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/order-confirmation/{reference}', [StoreController::class, 'orderSuccess'])->name('order-success');
    Route::get('/{product}', [StoreController::class, 'show'])->name('show');
});

// Contact & Newsletter
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/newsletter/subscribe', [ContactController::class, 'subscribe'])->name('newsletter.subscribe');

// SEO XML Sitemap & Robots
Route::get('/sitemap.xml', function () {
    $content = view('sitemap')->render();
    return response($content, 200)->header('Content-Type', 'text/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $robots = "User-agent: *\nAllow: /\nSitemap: " . url('/sitemap.xml') . "\n";
    return response($robots, 200)->header('Content-Type', 'text/plain');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication & Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Public Staff Invitation Accept Flow
    Route::get('/invitations/{token}', [AdminTeamController::class, 'showAcceptInvitation'])->name('invitations.accept');
    Route::post('/invitations/{token}', [AdminTeamController::class, 'processAcceptInvitation'])->name('invitations.process');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');

        // Admin Profile & Security
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password');

        // Team & Roles Management (Super Admin & Admin with manage_team permission)
        Route::middleware('can.admin:manage_team')->prefix('team')->name('team.')->group(function () {
            Route::get('/', [AdminTeamController::class, 'index'])->name('index');
            Route::get('/invite', [AdminTeamController::class, 'createInvitation'])->name('invite');
            Route::post('/invite', [AdminTeamController::class, 'sendInvitation'])->name('invitations.send');
            Route::delete('/invitations/{invitation}', [AdminTeamController::class, 'revokeInvitation'])->name('invitations.revoke');
            Route::post('/invitations/{invitation}/resend', [AdminTeamController::class, 'resendInvitation'])->name('invitations.resend');
            Route::get('/{user}/edit', [AdminTeamController::class, 'editUser'])->name('edit');
            Route::put('/{user}', [AdminTeamController::class, 'updateUser'])->name('update');
            Route::post('/{user}/status', [AdminTeamController::class, 'toggleUserStatus'])->name('status');
            Route::delete('/{user}', [AdminTeamController::class, 'deleteUser'])->name('delete');
        });

        // Site Content & Settings
        Route::get('/settings/{group?}', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/{group}', [AdminSettingController::class, 'update'])->name('settings.update');

        // Resources CRUD
        Route::resource('coaching', AdminCoachingServiceController::class);
        Route::resource('coaches', AdminCoachController::class);
        Route::resource('consulting', AdminConsultingServiceController::class);
        Route::resource('posts', AdminPostController::class);
        Route::resource('podcasts', AdminPodcastController::class);
        Route::resource('videos', AdminVideoController::class);
        Route::resource('testimonials', AdminTestimonialController::class);
        Route::resource('products', AdminProductController::class);

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Submissions & Subscribers
        Route::get('/inbox', [AdminInboxController::class, 'index'])->name('inbox.index');
        Route::get('/inbox/{submission}', [AdminInboxController::class, 'show'])->name('inbox.show');
        Route::delete('/inbox/{submission}', [AdminInboxController::class, 'destroy'])->name('inbox.destroy');
        Route::get('/subscribers', [AdminInboxController::class, 'subscribers'])->name('subscribers.index');

        // Media Library
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{media}', [AdminMediaController::class, 'destroy'])->name('media.destroy');
    });
});
