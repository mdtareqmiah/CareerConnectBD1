<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminInboxFilterRequest;
use App\Models\ContactMessage;
use App\Models\Feedback;
use App\Models\SupportTicket;
use App\Services\CommunicationDashboardService;
use App\Services\ContactService;
use App\Services\FeedbackService;
use App\Services\SupportTicketService;
use Illuminate\View\View;

class CommunicationInboxController extends Controller
{
    public function __construct(
        private readonly ContactService $contactService,
        private readonly FeedbackService $feedbackService,
        private readonly SupportTicketService $supportTicketService,
        private readonly CommunicationDashboardService $communicationDashboardService,
    ) {
    }

    public function index(AdminInboxFilterRequest $request): View
    {
        $this->authorize('viewAny', ContactMessage::class);

        $filters = $request->validated();
        $tab = $filters['tab'] ?? 'contacts';

        return view('admin.communications.index', [
            'activeTab' => $tab,
            'filters' => $filters,
            'stats' => $this->communicationDashboardService->getStats(),
            'monthlyGraph' => $this->communicationDashboardService->monthlyGraph(),
            'recentActivity' => $this->communicationDashboardService->recentActivity(),
            'contacts' => $tab === 'contacts' ? $this->contactService->paginateForAdmin($filters) : null,
            'feedbackItems' => $tab === 'feedback' ? $this->feedbackService->paginateForAdmin($filters) : null,
            'tickets' => $tab === 'tickets' ? $this->supportTicketService->paginateForAdmin($filters) : null,
            'contactStatuses' => ContactMessage::STATUSES,
            'feedbackStatuses' => Feedback::STATUSES,
            'ticketStatuses' => SupportTicket::STATUSES,
        ]);
    }
}
