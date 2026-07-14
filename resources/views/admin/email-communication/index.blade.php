@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h4 mb-1">Email Communication</h2>
                        <p class="text-muted mb-0">Review outgoing email activity and resend failed messages.</p>
                    </div>
                </div>

                <form method="GET" class="row g-2 mb-3">
                    <div class="col-md-5">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search recipient, subject, or type">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All statuses</option>
                            @foreach (['queued','sent','failed'] as $status)
                                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-primary w-100">Filter</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Recipient</th>
                                <th>Subject</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Sent At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td>{{ $log->recipient }}</td>
                                    <td>{{ $log->subject }}</td>
                                    <td>{{ $log->type }}</td>
                                    <td><span class="badge bg-info text-dark">{{ ucfirst($log->status) }}</span></td>
                                    <td>{{ $log->sent_at?->format('M d, Y H:i') ?? '—' }}</td>
                                    <td>
                                        @if ($log->status === 'failed' || $log->status === 'queued')
                                            <form method="POST" action="{{ route('admin.email-logs.resend', $log) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-primary">Resend</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-muted">No email activity found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $logs->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
