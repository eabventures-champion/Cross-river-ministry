<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        seo()
            ->title('About Us — Our Story & Mission')
            ->description(setting('about.hero_text'))
            ->image(setting('about.hero_image'));

        $coaches = Coach::active()->get();
        $values = setting_lines('about.values');
        $whyUs = setting_lines('about.why');

        return view('pages.about', compact('coaches', 'values', 'whyUs'));
    }
}
