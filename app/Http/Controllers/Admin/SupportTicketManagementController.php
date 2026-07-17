<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateSupportTicketStatusRequest;
use App\Models\SupportTicket;
use App\Services\SupportTicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupportTicketManagementController extends Controller
{
    public function __construct(private readonly SupportTicketService $supportTicketService)
    {
    }

    public function show(SupportTicket $supportTicket): View
    {
        $this->authorize('view', $supportTicket);

        return view('admin.communications.ticket-show', [
            'ticket' => $supportTicket->load(['messages.sender', 'user', 'assignedAdmin']),
            'statuses' => SupportTicket::STATUSES,
        ]);
    }

    public function updateStatus(UpdateSupportTicketStatusRequest $request, SupportTicket $supportTicket): RedirectResponse
    {
        $this->authorize('update', $supportTicket);

        $this->supportTicketService->updateStatus($supportTicket->load('user'), $request->validated()['status'], $request->user());

        return redirect()->back()->with('success', 'Ticket status updated successfully.');
    }

    public function destroy(SupportTicket $supportTicket): RedirectResponse
    {
        $this->authorize('delete', $supportTicket);

        $this->supportTicketService->delete($supportTicket);

        return redirect()->route('admin.communications.index', ['tab' => 'tickets'])->with('success', 'Ticket deleted successfully.');
    }
}
