<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use App\Models\Subscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InboxController extends Controller
{
    public function index(Request $request): View
    {
        $query = ContactSubmission::query();

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        $submissions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.inbox.index', compact('submissions'));
    }

    public function show(ContactSubmission $submission): View
    {
        $submission->markAsRead();

        return view('admin.inbox.show', compact('submission'));
    }

    public function destroy(ContactSubmission $submission): RedirectResponse
    {
        $submission->delete();

        return redirect()->route('admin.inbox.index')->with('success', 'Submission deleted.');
    }

    public function subscribers(): View
    {
        $subscribers = Subscriber::latest()->paginate(20);

        return view('admin.inbox.subscribers', compact('subscribers'));
    }
}
