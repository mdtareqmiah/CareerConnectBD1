@extends('layouts.admin')

@php
    $graphLabels = $monthlyGraph['labels'] ?? [];
    $graphContacts = $monthlyGraph['contacts'] ?? [];
    $graphFeedback = $monthlyGraph['feedback'] ?? [];
    $graphTickets = $monthlyGraph['tickets'] ?? [];
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Communication Center</h1>
        <p class="text-muted mb-0">Manage contact messages, feedback, and support tickets.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total Contacts</div><div class="h4 mb-0">{{ $stats['total_contacts'] }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Unread Contacts</div><div class="h4 mb-0">{{ $stats['unread_contacts'] }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Open Tickets</div><div class="h4 mb-0">{{ $stats['open_tickets'] }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Pending Tickets</div><div class="h4 mb-0">{{ $stats['pending_tickets'] }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Resolved Tickets</div><div class="h4 mb-0">{{ $stats['resolved_tickets'] }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total Feedback</div><div class="h4 mb-0">{{ $stats['total_feedback'] }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Avg Rating</div><div class="h4 mb-0">{{ $stats['average_rating'] }}</div></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h2 class="h6 mb-3">Monthly Communication Volume</h2>
        <canvas id="communicationsChart" height="100"></canvas>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ route('admin.communications.index', array_merge(request()->query(), ['tab' => 'contacts'])) }}" class="btn btn-sm {{ $activeTab === 'contacts' ? 'btn-primary' : 'btn-outline-primary' }}">Contact Messages</a>
            <a href="{{ route('admin.communications.index', array_merge(request()->query(), ['tab' => 'feedback'])) }}" class="btn btn-sm {{ $activeTab === 'feedback' ? 'btn-primary' : 'btn-outline-primary' }}">Feedback</a>
            <a href="{{ route('admin.communications.index', array_merge(request()->query(), ['tab' => 'tickets'])) }}" class="btn btn-sm {{ $activeTab === 'tickets' ? 'btn-primary' : 'btn-outline-primary' }}">Support Tickets</a>
        </div>

        <form method="GET" class="row g-2 mb-3">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <div class="col-md-5"><input type="text" class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search..."></div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All statuses</option>
                    @php
                        $statusOptions = $activeTab === 'contacts' ? $contactStatuses : ($activeTab === 'feedback' ? $feedbackStatuses : $ticketStatuses);
                    @endphp
                    @foreach($statusOptions as $statusOption)
                        <option value="{{ $statusOption }}" @selected(($filters['status'] ?? '') === $statusOption)>{{ ucwords(str_replace('_', ' ', $statusOption)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Latest</option>
                    <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest</option>
                    @if($activeTab === 'feedback')
                        <option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>Highest Rating</option>
                    @endif
                    @if($activeTab === 'tickets')
                        <option value="priority" @selected(($filters['sort'] ?? '') === 'priority')>Priority</option>
                    @endif
                </select>
            </div>
            <div class="col-md-2 d-grid"><button class="btn btn-primary">Apply</button></div>
        </form>

        @if($activeTab === 'contacts')
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead><tr><th>Name</th><th>Email</th><th>Subject</th><th>Category</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td>{{ $contact->full_name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->subject }}</td>
                                <td>{{ ucwords($contact->category) }}</td>
                                <td><span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $contact->status)) }}</span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#contactRow{{ $contact->id }}">Manage</button>
                                </td>
                            </tr>
                            <tr class="collapse" id="contactRow{{ $contact->id }}">
                                <td colspan="6">
                                    <div class="border rounded p-3">
                                        <p class="mb-2">{{ $contact->message }}</p>
                                        <div class="row g-2">
                                            <div class="col-lg-4">
                                                <form method="POST" action="{{ route('admin.communications.contacts.status', $contact) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="input-group input-group-sm">
                                                        <select name="status" class="form-select">
                                                            @foreach($contactStatuses as $status)
                                                                <option value="{{ $status }}" @selected($contact->status === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button class="btn btn-outline-primary">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-lg-8">
                                                <form method="POST" action="{{ route('admin.communications.contacts.reply', $contact) }}" class="d-grid gap-2">
                                                    @csrf
                                                    <input type="text" class="form-control form-control-sm" name="subject" value="Re: {{ $contact->subject }}" required>
                                                    <textarea class="form-control form-control-sm" name="message" rows="3" required></textarea>
                                                    <div class="text-end">
                                                        <button class="btn btn-sm btn-primary">Send Reply</button>
                                                    </div>
                                                </form>
                                                <form method="POST" action="{{ route('admin.communications.contacts.destroy', $contact) }}" class="mt-2 text-end">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this contact message?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No contact messages found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $contacts?->links() }}
        @elseif($activeTab === 'feedback')
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead><tr><th>User</th><th>Subject</th><th>Type</th><th>Rating</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse($feedbackItems as $item)
                            <tr>
                                <td>{{ $item->user?->name }}</td>
                                <td>{{ $item->subject }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $item->type)) }}</td>
                                <td>{{ $item->rating }}/5</td>
                                <td><span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $item->status)) }}</span></td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.feedback.show', $item) }}">Open</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No feedback found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $feedbackItems?->links() }}
        @else
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead><tr><th>Ticket</th><th>User</th><th>Priority</th><th>Status</th><th>Updated</th><th></th></tr></thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket_number }}<div class="small text-muted">{{ $ticket->subject }}</div></td>
                                <td>{{ $ticket->user?->name }}</td>
                                <td><span class="badge {{ $ticket->priority === 'urgent' ? 'bg-danger' : ($ticket->priority === 'high' ? 'bg-warning text-dark' : ($ticket->priority === 'medium' ? 'bg-info text-dark' : 'bg-secondary')) }}">{{ ucfirst($ticket->priority) }}</span></td>
                                <td><span class="badge bg-secondary">{{ ucwords(str_replace('_', ' ', $ticket->status)) }}</span></td>
                                <td>{{ $ticket->updated_at?->diffForHumans() }}</td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.support-tickets.show', $ticket) }}">Open</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No tickets found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $tickets?->links() }}
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h2 class="h6">Recent Activity</h2>
        <ul class="list-group list-group-flush">
            @forelse($recentActivity as $activity)
                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                    <div>
                        <div class="fw-semibold">{{ strtoupper($activity['type']) }} - {{ $activity['label'] }}</div>
                        <div class="small text-muted">{{ $activity['meta'] }}</div>
                    </div>
                    <small class="text-muted">{{ $activity['created_at']?->diffForHumans() }}</small>
                </li>
            @empty
                <li class="list-group-item px-0 text-muted">No recent communication activity.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const chartEl = document.getElementById('communicationsChart');
    if (chartEl) {
        new Chart(chartEl, {
            type: 'line',
            data: {
                labels: @json($graphLabels),
                datasets: [
                    { label: 'Contacts', data: @json($graphContacts), borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,.15)', tension: .3 },
                    { label: 'Feedback', data: @json($graphFeedback), borderColor: '#198754', backgroundColor: 'rgba(25,135,84,.15)', tension: .3 },
                    { label: 'Tickets', data: @json($graphTickets), borderColor: '#dc3545', backgroundColor: 'rgba(220,53,69,.15)', tension: .3 },
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    }
</script>
@endsection
