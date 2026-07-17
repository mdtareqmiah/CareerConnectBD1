<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportTicketRequest;
use App\Models\SupportTicket;
use App\Services\SupportTicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function __construct(private readonly SupportTicketService $supportTicketService)
    {
    }

    public function index(): View
    {
        $this->authorize('viewAny', SupportTicket::class);

        return view('support-tickets.index', [
            'tickets' => $this->supportTicketService->paginateForUser(auth()->user()),
            'statuses' => SupportTicket::STATUSES,
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', SupportTicket::class);

        return view('support-tickets.create', [
            'priorities' => SupportTicket::PRIORITIES,
        ]);
    }

    public function store(StoreSupportTicketRequest $request): RedirectResponse
    {
        $this->authorize('create', SupportTicket::class);

        $ticket = $this->supportTicketService->create($request->user(), $request->validated());

        return redirect()->route('support-tickets.show', $ticket)->with('success', 'Support ticket created successfully.');
    }

    public function show(SupportTicket $supportTicket): View
    {
        $this->authorize('view', $supportTicket);

        return view('support-tickets.show', [
            'ticket' => $supportTicket->load(['messages.sender', 'user']),
        ]);
    }
}
