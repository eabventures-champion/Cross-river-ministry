<?php

namespace App\Http\Controllers;

use App\Models\CoachingService;
use Illuminate\View\View;

class CoachingController extends Controller
{
    public function index(): View
    {
        seo()
            ->title('Life Coaching Services — Spiritual, Family, Managerial, Vocational, Educational')
            ->description('Explore our 5 core Christian life coaching disciplines designed to help you cross life’s rivers and fulfill God’s purpose.');

        $services = CoachingService::active()->get();

        return view('pages.coaching.index', compact('services'));
    }

    public function show(CoachingService $service): View
    {
        seo()
            ->title($service->title)
            ->description($service->excerpt)
            ->image($service->imageUrl());

        $otherServices = CoachingService::active()->where('id', '!=', $service->id)->take(4)->get();

        return view('pages.coaching.show', compact('service', 'otherServices'));
    }
}
