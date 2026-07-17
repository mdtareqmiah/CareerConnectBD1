@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Employer Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Employers</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Employers</div><div class="h5 mb-0">{{ $stats['total_employers'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Verified</div><div class="h5 mb-0">{{ $stats['verified'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Pending</div><div class="h5 mb-0">{{ $stats['pending'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Rejected</div><div class="h5 mb-0">{{ $stats['rejected'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Suspended</div><div class="h5 mb-0">{{ $stats['suspended'] }}</div></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.employers.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Employer, email, company, industry">
            </div>
            <div class="col-lg-2">
                <label class="form-label">Verification</label>
                <select name="verification" class="form-select">
                    <option value="">All</option>
                    <option value="pending" @selected(($filters['verification'] ?? '') === 'pending')>Pending</option>
                    <option value="verified" @selected(($filters['verification'] ?? '') === 'verified')>Verified</option>
                    <option value="rejected" @selected(($filters['verification'] ?? '') === 'rejected')>Rejected</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">All</option>
                    <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                    <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
                </select>
            </div>
            <div class="col-lg-2">
                <label class="form-label">Sort</label>
                <select name="sort" class="form-select">
                    <option value="newest" @selected(($filters['sort'] ?? 'newest') === 'newest')>Newest</option>
                    <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Oldest</option>
                </select>
            </div>
            <div class="col-lg-1 d-grid"><button class="btn btn-outline-primary" type="submit">Go</button></div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Employer Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Verification</th>
                    <th>Account Status</th>
                    <th>Registered</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($employers as $employer)
                <tr>
                    <td>{{ $employer->name }}</td>
                    <td>{{ $employer->email }}</td>
                    <td>
                        @if($employer->company)
                            <a href="{{ route('admin.companies.show', $employer->company) }}">{{ $employer->company->company_name }}</a>
                        @else
                            <span class="text-muted">No company</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $verification = $employer->company->verification_status ?? 'pending';
                            $verificationClass = match($verification) {
                                'verified' => 'success',
                                'rejected' => 'danger',
                                default => 'warning text-dark',
                            };
                        @endphp
                        <span class="badge bg-{{ $verificationClass }}">{{ ucfirst($verification) }}</span>
                    </td>
                    <td><span class="badge bg-{{ $employer->is_active ? 'success' : 'secondary' }}">{{ $employer->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>{{ $employer->created_at?->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1 flex-wrap">
                            <a href="{{ route('admin.employers.show', $employer) }}" class="btn btn-sm btn-outline-primary">View Employer</a>
                            @if($employer->company)
                                <a href="{{ route('admin.companies.show', $employer->company) }}" class="btn btn-sm btn-outline-secondary">View Company</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No employers found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($employers->hasPages())
        <div class="card-footer bg-white">{{ $employers->links() }}</div>
    @endif
</div>
@endsection
