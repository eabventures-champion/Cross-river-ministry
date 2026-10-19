<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\CoachingService;
use App\Models\PodcastEpisode;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\Video;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        seo()
            ->title('Your Personal Spiritual Coach for a Better Life')
            ->description(setting('home.hero_text'))
            ->image(setting('home.hero_image'));

        $services = CoachingService::active()->take(6)->get();
        $coaches = Coach::active()->take(3)->get();
        $testimonials = Testimonial::published()->take(6)->get();
        $latestPosts = Post::published()->with(['category', 'author'])->latest('published_at')->take(3)->get();
        $featuredEpisodes = PodcastEpisode::published()->with('podcast')->latest('published_at')->take(2)->get();
        $featuredVideo = Video::published()->where('is_featured', true)->latest('published_at')->first()
            ?? Video::published()->latest('published_at')->first();

        return view('pages.home', compact(
            'services',
            'coaches',
            'testimonials',
            'latestPosts',
            'featuredEpisodes',
            'featuredVideo'
        ));
    }
}
