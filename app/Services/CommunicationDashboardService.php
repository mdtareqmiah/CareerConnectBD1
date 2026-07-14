<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\Feedback;
use App\Models\SupportTicket;

class CommunicationDashboardService
{
    public function getStats(): array
    {
        return [
            'total_contacts' => ContactMessage::count(),
            'unread_contacts' => ContactMessage::where('status', 'unread')->count(),
            'open_tickets' => SupportTicket::where('status', 'open')->count(),
            'pending_tickets' => SupportTicket::where('status', 'pending')->count(),
            'resolved_tickets' => SupportTicket::where('status', 'resolved')->count(),
            'total_feedback' => Feedback::count(),
            'average_rating' => round((float) Feedback::avg('rating'), 2),
        ];
    }

    public function monthlyGraph(): array
    {
        $labels = [];
        $contacts = [];
        $feedback = [];
        $tickets = [];

        for ($offset = 11; $offset >= 0; $offset--) {
            $month = now()->startOfMonth()->subMonths($offset);
            $labels[] = $month->format('M Y');

            $contacts[] = ContactMessage::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $feedback[] = Feedback::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $tickets[] = SupportTicket::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        return [
            'labels' => $labels,
            'contacts' => $contacts,
            'feedback' => $feedback,
            'tickets' => $tickets,
        ];
    }

    public function recentActivity(int $limit = 10): array
    {
        $contactItems = ContactMessage::query()
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(fn (ContactMessage $message) => [
                'type' => 'contact',
                'label' => $message->subject,
                'meta' => $message->email,
                'created_at' => $message->created_at,
            ]);

        $feedbackItems = Feedback::query()
            ->with('user')
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(fn (Feedback $item) => [
                'type' => 'feedback',
                'label' => $item->subject,
                'meta' => $item->user?->email,
                'created_at' => $item->created_at,
            ]);

        $ticketItems = SupportTicket::query()
            ->with('user')
            ->latest('created_at')
            ->limit($limit)
            ->get()
            ->map(fn (SupportTicket $ticket) => [
                'type' => 'ticket',
                'label' => $ticket->ticket_number,
                'meta' => $ticket->user?->email,
                'created_at' => $ticket->created_at,
            ]);

        return $contactItems
            ->merge($feedbackItems)
            ->merge($ticketItems)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values()
            ->all();
    }
}
