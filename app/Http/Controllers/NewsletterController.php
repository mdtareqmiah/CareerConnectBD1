<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        // Validate the email
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // Check if email already exists
        $newsletter = Newsletter::where('email', $validated['email'])->first();

        if ($newsletter) {
            // If already subscribed, update status
            if ($newsletter->status === 'subscribed') {
                return redirect()->back()->with('info', 'You are already subscribed to our newsletter!');
            } else {
                // Reactivate subscription
                $newsletter->update([
                    'status' => 'subscribed',
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null,
                ]);
                return redirect()->back()->with('success', 'Welcome back! You have been resubscribed to our newsletter.');
            }
        }

        // Create new newsletter subscription
        Newsletter::create([
            'email' => $validated['email'],
            'status' => 'subscribed',
            'subscribed_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Thank you for subscribing! Check your inbox for exclusive updates.');
    }
}

