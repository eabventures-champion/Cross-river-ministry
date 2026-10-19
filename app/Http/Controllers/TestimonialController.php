<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function __invoke(): View
    {
        seo()
            ->title('Testimonials & Stories of Victory')
            ->description('Read inspiring true stories of life transformation, restored families, and personal breakthroughs through Cross Rivers Ministries.');

        $featured = Testimonial::published()->where('is_featured', true)->first();
        $testimonials = Testimonial::published()->get();

        return view('pages.testimonials', compact('featured', 'testimonials'));
    }
}
