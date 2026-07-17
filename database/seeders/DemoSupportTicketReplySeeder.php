<?php

namespace Database\Seeders;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSupportTicketReplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@careerconnectbd.com')->first()
            ?? User::whereHas('role', fn ($query) => $query->where('slug', 'admin'))->first();

        $tickets = SupportTicket::all();

        if (! $admin || $tickets->isEmpty()) {
            return;
        }

        $userReplies = [
            'Thank you for the update. I will try the suggested steps now.',
            'I appreciate the quick response. I will follow the instructions and share the result.',
            'That helps a lot. I was confused about the process before.',
            'I have attached the screenshot as requested and I hope it helps.',
            'I am still seeing the issue, so I would like a bit more guidance.',
        ];

        $adminReplies = [
            'We have reviewed your report and the issue appears to be related to the file format.',
            'Please try the steps below and let us know if the problem still persists.',
            'We have noted your concern and escalated it to our support team for further review.',
            'Thank you for the detail. We have updated your ticket with the additional information you sent.',
            'We are following up on this and will continue monitoring the status for you.',
        ];

        foreach ($tickets as $ticket) {
            $replyCount = rand(2, 5);
            $messages = [];

            for ($i = 0; $i < $replyCount; $i++) {
                $sender = $i % 2 === 0 ? $ticket->user : $admin;
                $message = $i % 2 === 0
                    ? $userReplies[array_rand($userReplies)]
                    : $adminReplies[array_rand($adminReplies)];

                $messages[] = ['sender' => $sender, 'message' => $message];
            }

            foreach ($messages as $entry) {
                SupportTicketMessage::firstOrCreate(
                    [
                        'support_ticket_id' => $ticket->id,
                        'sender_id' => $entry['sender']->id,
                        'message' => $entry['message'],
                    ],
                    []
                );
            }

            $ticket->update(['last_reply_at' => now()->subHours(rand(1, 8))]);
        }
    }
}
