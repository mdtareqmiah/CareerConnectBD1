<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportTicketReplyRequest;
use App\Models\SupportTicket;
use App\Services\TicketReplyService;
use Illuminate\Http\RedirectResponse;

class SupportTicketReplyController extends Controller
{
    public function __construct(private readonly TicketReplyService $ticketReplyService)
    {
    }

    public function store(StoreSupportTicketReplyRequest $request, SupportTicket $supportTicket): RedirectResponse
    {
        $this->authorize('reply', $supportTicket);

        $this->ticketReplyService->createReply($supportTicket, $request->user(), $request->validated());

        if ($request->user()->role?->slug === 'admin') {
            return redirect()->route('admin.support-tickets.show', $supportTicket)->with('success', 'Reply sent successfully.');
        }

        return redirect()->route('support-tickets.show', $supportTicket)->with('success', 'Reply added successfully.');
    }
}
