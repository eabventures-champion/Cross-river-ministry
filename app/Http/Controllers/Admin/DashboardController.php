<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\CoachingService;
use App\Models\ConsultingService;
use App\Models\ContactSubmission;
use App\Models\Order;
use App\Models\PodcastEpisode;
use App\Models\Post;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\Testimonial;
use App\Models\Video;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'coaching_services' => CoachingService::count(),
            'coaches' => Coach::count(),
            'consulting_services' => ConsultingService::count(),
            'posts' => Post::count(),
            'episodes' => PodcastEpisode::count(),
            'videos' => Video::count(),
            'testimonials' => Testimonial::count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'unread_messages' => ContactSubmission::unread()->count(),
            'subscribers' => Subscriber::count(),
        ];

        $recentSubmissions = ContactSubmission::latest()->take(5)->get();
        $recentOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSubmissions', 'recentOrders'));
    }
}
