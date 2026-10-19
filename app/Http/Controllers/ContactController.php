<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        seo()
            ->title('Contact Us — Start Your Journey')
            ->description('Connect with Cross Rivers Ministries. Send us a message, dial our Coaching Advice Line, or partner with us.');

        return view('pages.contact');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'reminder' => ['nullable', 'string', 'in:Loved,Special,Resilient'], // Mandated reminder from original contact form
        ]);

        ContactSubmission::create([
            'type' => 'contact',
            'name' => trim($validated['first_name'] . ' ' . $validated['last_name']),
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?: 'New Contact Message',
            'message' => $validated['message'],
            'meta' => [
                'reminder_selected' => $validated['reminder'] ?? null,
            ],
        ]);

        return back()->with('success', 'Thank you! Your message has been received. Our ministry coaches will respond to you shortly.');
    }

    public function subscribe(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        Subscriber::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'] ?? null,
                'source' => $validated['source'] ?? 'website',
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you for subscribing to our Quarterly Newsletter!',
            ]);
        }

        return back()->with('newsletter_success', 'Thank you for subscribing to our Quarterly Newsletter!');
    }
}
