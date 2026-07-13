@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Company Management</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Companies</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total</div><div class="h5 mb-0">{{ $stats['total_companies'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Verified</div><div class="h5 mb-0">{{ $stats['verified'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Pending</div><div class="h5 mb-0">{{ $stats['pending'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Rejected</div><div class="h5 mb-0">{{ $stats['rejected'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Suspended</div><div class="h5 mb-0">{{ $stats['suspended'] }}</div></div></div></div>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.companies.index') }}" class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label">Search</label>
                <input type="text" class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Company, employer, email, industry">
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
                    <th>Logo</th>
                    <th>Company</th>
                    <th>Industry</th>
                    <th>Location</th>
                    <th>Website</th>
                    <th>Employer</th>
                    <th>Verification</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($companies as $company)
                <tr>
                    <td><img src="{{ $company->company_logo_url }}" alt="Logo" width="40" height="40" class="rounded border object-fit-cover"></td>
                    <td>{{ $company->company_name }}</td>
                    <td>{{ $company->industry }}</td>
                    <td>{{ $company->city }}, {{ $company->country }}</td>
                    <td>
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" rel="noopener">Visit</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($company->employer)
                            <a href="{{ route('admin.employers.show', $company->employer) }}">{{ $company->employer->name }}</a>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $verificationClass = match($company->verification_status) {
                                'verified' => 'success',
                                'rejected' => 'danger',
                                default => 'warning text-dark',
                            };
                        @endphp
                        <span class="badge bg-{{ $verificationClass }}">{{ ucfirst($company->verification_status ?? 'pending') }}</span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $company->is_active ? 'success' : 'secondary' }}">{{ $company->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td>{{ $company->created_at?->format('M d, Y') }}</td>
                    <td class="text-end">
                        <div class="d-flex flex-wrap justify-content-end gap-1">
                            <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <a href="{{ route('admin.companies.show', $company) }}#jobs" class="btn btn-sm btn-outline-dark">View Jobs</a>
                            @if(($company->verification_status ?? 'pending') !== 'verified')
                                <form method="POST" action="{{ route('admin.companies.approve', $company) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success" type="submit">Approve</button>
                                </form>
                            @endif
                            @if(($company->verification_status ?? 'pending') !== 'rejected')
                                <form method="POST" action="{{ route('admin.companies.reject', $company) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Reject</button>
                                </form>
                            @endif
                            @if($company->is_active)
                                <form method="POST" action="{{ route('admin.companies.suspend', $company) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-warning" type="submit">Suspend</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.companies.activate', $company) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-outline-success" type="submit">Activate</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10" class="text-center text-muted py-4">No companies found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($companies->hasPages())
        <div class="card-footer bg-white">{{ $companies->links() }}</div>
    @endif
</div>
@endsection
