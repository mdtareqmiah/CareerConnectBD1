@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4">
                    <div>
                        <h2 class="h4 mb-1">Notifications</h2>
                        <p class="text-muted mb-0">Stay up to date with the latest activity and updates.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary btn-sm">All</a>
                        <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" class="btn btn-outline-primary btn-sm">Unread</a>
                        <a href="{{ route('notifications.index', ['filter' => 'read']) }}" class="btn btn-outline-secondary btn-sm">Read</a>
                        <form method="POST" action="{{ route('notifications.read-all') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-primary btn-sm">Mark all read</button>
                        </form>
                    </div>
                </div>

                @if ($notifications->isEmpty())
                    <div class="alert alert-light border">No notifications found.</div>
                @else
                    <div class="list-group">
                        @foreach ($notifications as $notification)
                            @php
                                $data = $notification->data ?? [];
                                $title = $data['title'] ?? 'Notification';
                                $message = $data['message'] ?? '';
                                $link = $data['link'] ?? null;
                            @endphp
                            <div class="list-group-item d-flex justify-content-between align-items-start gap-3 {{ $notification->read_at ? '' : 'border-primary' }}">
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($notification->read_at)
                                            <span class="badge bg-light text-muted">Read</span>
                                        @else
                                            <span class="badge bg-primary">Unread</span>
                                        @endif
                                        <h6 class="mb-0">{{ $title }}</h6>
                                    </div>
                                    <p class="mb-1 mt-2 text-muted">{{ $message }}</p>
                                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    @if ($link)
                                        <a href="{{ $link }}" class="btn btn-sm btn-outline-primary">Open</a>
                                    @endif
                                    @if (is_null($notification->read_at))
                                        <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">Mark read</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('notifications.destroy', $notification) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
