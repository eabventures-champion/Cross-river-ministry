<?php

namespace App\Http\Controllers;

use App\Models\ConsultingService;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultingController extends Controller
{
    public function index(): View
    {
        seo()
            ->title('Consulting & Speaking Engagements')
            ->description('Corporate consulting, keynote speaking, and biblical workshops for businesses, churches, and organizations.');

        $speaking = ConsultingService::active()->ofType('speaking')->get();
        $workshops = ConsultingService::active()->ofType('workshop')->get();
        $consulting = ConsultingService::active()->ofType('consulting')->get();

        return view('pages.consulting.index', compact('speaking', 'workshops', 'consulting'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'organization' => ['nullable', 'string', 'max:255'],
            'event_type' => ['required', 'string', 'max:100'],
            'estimated_date' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        ContactSubmission::create([
            'type' => 'consulting',
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => 'Consulting/Speaking Inquiry: ' . $validated['event_type'],
            'message' => $validated['message'],
            'meta' => [
                'organization' => $validated['organization'] ?? null,
                'event_type' => $validated['event_type'],
                'estimated_date' => $validated['estimated_date'] ?? null,
            ],
        ]);

        return back()->with('success', 'Thank you for your consultation request! Jane Audrey and our team will be in touch shortly.');
    }
}
