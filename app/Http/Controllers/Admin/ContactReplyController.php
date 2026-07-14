<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ContactReplyController extends Controller
{
    public function __construct(private readonly MailService $mailService)
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipient' => ['required', 'email'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $this->mailService->send('contact_reply', $validated['recipient'], [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'url' => route('contact.create'),
        ], $request->user());

        return redirect()->back()->with('success', 'Reply queued and sent.');
    }
}
