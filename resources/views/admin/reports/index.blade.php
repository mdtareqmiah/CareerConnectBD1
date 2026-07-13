@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">Reports Dashboard</h1>
        <p class="text-muted mb-0">System-wide metrics and trends</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Users</div><div class="h5 mb-0">{{ $cards['total_users'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Employers</div><div class="h5 mb-0">{{ $cards['total_employers'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Companies</div><div class="h5 mb-0">{{ $cards['total_companies'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Total Jobs</div><div class="h5 mb-0">{{ $cards['total_jobs'] }}</div></div></div></div>

    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Published Jobs</div><div class="h5 mb-0">{{ $cards['published_jobs'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Applications</div><div class="h5 mb-0">{{ $cards['applications'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Active Jobs</div><div class="h5 mb-0">{{ $cards['active_jobs'] }}</div></div></div></div>
    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Closed Jobs</div><div class="h5 mb-0">{{ $cards['closed_jobs'] }}</div></div></div></div>

    <div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="small text-muted">Resume Builders</div><div class="h5 mb-0">{{ $cards['resume_builders'] }}</div></div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h2 class="h6 mb-3">Monthly Jobs vs Applications</h2>
                <canvas id="monthlyChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h2 class="h6 mb-3">Users by Role</h2>
                @forelse($usersByRole as $row)
                    <div class="d-flex justify-content-between small mb-2">
                        <span>{{ $row['role'] }}</span>
                        <span>{{ $row['count'] }}</span>
                    </div>
                @empty
                    <div class="text-muted small">No data.</div>
                @endforelse
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 mb-3">Companies by Industry</h2>
                @forelse($companiesByIndustry as $row)
                    <div class="mb-2">
                        <div class="d-flex justify-content-between small">
                            <span>{{ $row['industry'] }}</span>
                            <span>{{ $row['count'] }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ max(5, min(100, $row['count'] * 5)) }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted small">No data.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const labels = @json($monthly['labels']);
const jobs = @json($monthly['jobs']);
const applications = @json($monthly['applications']);

const chartElement = document.getElementById('monthlyChart');
if (chartElement) {
    new Chart(chartElement, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Jobs',
                    data: jobs,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,.15)',
                    tension: 0.3,
                },
                {
                    label: 'Applications',
                    data: applications,
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25,135,84,.15)',
                    tension: 0.3,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
}
</script>
@endsection
