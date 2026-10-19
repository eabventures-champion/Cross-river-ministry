<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AdviceLineController extends Controller
{
    public function __invoke(): View
    {
        seo()
            ->title('Coaching Advice Line — Real Hope & Personal Guidance')
            ->description(setting('advice_line.hero_text'))
            ->image(setting('advice_line.hero_image'));

        $whoCanUse = setting_lines('advice_line.who_list');
        $expectations = setting_lines('advice_line.expect_list');
        $steps = setting_lines('advice_line.steps');

        return view('pages.advice-line', compact('whoCanUse', 'expectations', 'steps'));
    }
}
