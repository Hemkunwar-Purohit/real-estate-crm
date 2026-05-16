@extends('crm.layouts.app')
@section('page_title', 'Dashboard')

@section('page_actions')
    <a href="{{ route('crm.leads.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Lead
    </a>
@endsection

@section('main_content')

{{-- Stats Cards --}}
<div class="row mb-3">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $stats['total_leads'] }}</h3><p>Active Leads</p></div>
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <a href="{{ route('crm.leads.index') }}" class="small-box-footer">
                View All <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $stats['total_clients'] }}</h3><p>Total Clients</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ route('crm.clients.index') }}" class="small-box-footer">
                View All <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $stats['total_properties'] }}</h3><p>Available Properties</p></div>
            <div class="icon"><i class="fas fa-building"></i></div>
            <a href="{{ route('crm.properties.index') }}" class="small-box-footer">
                View All <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner"><h3>{{ $stats['active_deals'] }}</h3><p>Active Deals</p></div>
            <div class="icon"><i class="fas fa-handshake"></i></div>
            <a href="{{ route('crm.deals.index') }}" class="small-box-footer">
                View All <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

{{-- Won This Month --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="card bg-gradient-success text-white">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Won This Month</h5>
                        <small>Total closed deal value</small>
                    </div>
                    <h2 class="mb-0">₹{{ number_format($wonThisMonth) }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Today's Site Visits --}}
@if($todayVisits->count() > 0)
<div class="row mb-3">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h6 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Today's Site Visits ({{ $todayVisits->count() }})</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Lead</th><th>Property</th><th>Agent</th><th>Time</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($todayVisits as $visit)
                        <tr>
                            <td><strong>{{ $visit->lead->name }}</strong><br><small>{{ $visit->lead->phone }}</small></td>
                            <td>{{ Str::limit($visit->property->title, 30) }}</td>
                            <td>{{ $visit->agent->name }}</td>
                            <td><span class="badge bg-primary">{{ $visit->visit_datetime->format('h:i A') }}</span></td>
                            <td>
                                <a href="{{ route('crm.site-visits.show', $visit) }}" class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-check"></i> Mark Done
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    {{-- Deals by Stage Chart --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Deals by Stage</h3></div>
            <div class="card-body">
                <canvas id="dealsByStageChart" height="250"></canvas>
            </div>
        </div>
    </div>

    {{-- Monthly Leads Chart --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Monthly Leads</h3></div>
            <div class="card-body">
                <canvas id="monthlyLeadsChart" height="250"></canvas>
            </div>
        </div>
    </div>

    {{-- Lead Sources --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Lead Sources</h3></div>
            <div class="card-body">
                <canvas id="leadSourcesChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- My Leads --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">My Leads</h3>
                <a href="{{ route('crm.leads.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Name</th><th>Phone</th><th>Status</th><th>Source</th></tr></thead>
                    <tbody>
                        @forelse($myLeads as $lead)
                        <tr>
                            <td><a href="{{ route('crm.leads.show', $lead) }}">{{ $lead->name }}</a></td>
                            <td>{{ $lead->phone }}</td>
                            <td>
                                <span class="badge bg-{{ $lead->status == 'new' ? 'info' : 'warning' }}">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                            <td>{{ config('crm.lead_sources')[$lead->source] ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No leads assigned</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Upcoming Site Visits --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Upcoming Visits</h3>
                <a href="{{ route('crm.site-visits.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead><tr><th>Lead</th><th>Property</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($upcomingSiteVisits as $visit)
                        <tr>
                            <td>{{ $visit->lead->name }}</td>
                            <td>{{ Str::limit($visit->property->title, 20) }}</td>
                            <td><span class="badge bg-primary">{{ $visit->visit_datetime->format('d M, h:i A') }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No upcoming visits</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Recent Deals --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Recent Deals</h3>
                <a href="{{ route('crm.deals.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Deal</th><th>Client</th><th>Property</th><th>Value</th><th>Stage</th><th>Agent</th></tr></thead>
                    <tbody>
                        @forelse($recentDeals as $deal)
                        @php
                            $stageColors = ['new'=>'secondary','site_visit'=>'info','negotiation'=>'warning','docs_pending'=>'primary','won'=>'success','lost'=>'danger'];
                        @endphp
                        <tr>
                            <td><a href="{{ route('crm.deals.show', $deal) }}">{{ $deal->title }}</a></td>
                            <td>{{ $deal->client->name }}</td>
                            <td>{{ $deal->property ? Str::limit($deal->property->title, 20) : '—' }}</td>
                            <td><strong>{{ $deal->currency }} {{ number_format($deal->deal_value) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $stageColors[$deal->stage] ?? 'secondary' }}">
                                    {{ config('crm.pipeline_stages')[$deal->stage] ?? $deal->stage }}
                                </span>
                            </td>
                            <td>{{ $deal->assignedAgent->name }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No deals yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 1. Deals by Stage
const stageLabels = @json(array_values(config('crm.pipeline_stages')));
const stageKeys   = @json(array_keys(config('crm.pipeline_stages')));
const stageData   = @json($dealsByStage);
const stageCounts = stageKeys.map(k => stageData[k] ? stageData[k].count : 0);

new Chart(document.getElementById('dealsByStageChart'), {
    type: 'doughnut',
    data: {
        labels: stageLabels,
        datasets: [{ data: stageCounts, backgroundColor: ['#6c757d','#17a2b8','#ffc107','#007bff','#28a745','#dc3545'] }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// 2. Monthly Leads
const monthNames  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const monthlyData = @json($monthlyLeads);
const months      = Object.keys(monthlyData).map(m => monthNames[parseInt(m) - 1]);
const counts      = Object.values(monthlyData);

new Chart(document.getElementById('monthlyLeadsChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{ label: 'New Leads', data: counts, backgroundColor: 'rgba(0,123,255,0.7)', borderRadius: 4 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

// 3. Lead Sources
const sourcesData   = @json($leadSources);
const sourceLabels  = sourcesData.map(s => s.source);
const sourceCounts  = sourcesData.map(s => s.count);

new Chart(document.getElementById('leadSourcesChart'), {
    type: 'pie',
    data: {
        labels: sourceLabels,
        datasets: [{ data: sourceCounts, backgroundColor: ['#007bff','#28a745','#ffc107','#17a2b8','#dc3545'] }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endsection