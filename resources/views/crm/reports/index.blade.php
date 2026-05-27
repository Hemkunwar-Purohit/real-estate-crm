@extends('crm.layouts.app')
@section('page_title', 'Reports & Analytics')

@section('main_content')

{{-- Summary Stats --}}
<div class="row mb-3">
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-info"><i class="fas fa-user-clock"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Leads</span>
                <span class="info-box-number">{{ $stats['total_leads'] }}</span>
                <div class="progress"><div class="progress-bar bg-info" style="width:{{ $stats['total_leads'] > 0 ? ($stats['converted_leads']/$stats['total_leads'])*100 : 0 }}%"></div></div>
                <span class="progress-description">{{ $stats['converted_leads'] }} converted</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-success"><i class="fas fa-handshake"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Won Deals</span>
                <span class="info-box-number">{{ $stats['won_deals'] }}</span>
                <div class="progress"><div class="progress-bar bg-success" style="width:{{ $stats['total_deals'] > 0 ? ($stats['won_deals']/$stats['total_deals'])*100 : 0 }}%"></div></div>
                <span class="progress-description">of {{ $stats['total_deals'] }} total</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-warning"><i class="fas fa-rupee-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Revenue</span>
                <span class="info-box-number">₹{{ number_format($stats['total_revenue']/100000, 1) }}L</span>
                <div class="progress"><div class="progress-bar bg-warning" style="width:75%"></div></div>
                <span class="progress-description">all time won deals</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-danger"><i class="fas fa-percentage"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Visit Conversion</span>
                <span class="info-box-number">{{ $conversionRate }}%</span>
                <div class="progress"><div class="progress-bar bg-danger" style="width:{{ $conversionRate }}%"></div></div>
                <span class="progress-description">{{ $totalVisits }} total visits</span>
            </div>
        </div>
    </div>
</div>

{{-- Revenue This Month vs Last Month --}}
<div class="row mb-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">This Month Revenue</p>
                        <h3 class="mb-0 text-success">₹{{ number_format($revenueThisMonth) }}</h3>
                    </div>
                    <div class="text-end">
                        <p class="text-muted mb-1">Last Month</p>
                        <h5 class="mb-0">₹{{ number_format($revenueLastMonth) }}</h5>
                    </div>
                </div>
                <div class="mt-2">
                    @if($revenueGrowth > 0)
                        <span class="text-success"><i class="fas fa-arrow-up"></i> {{ $revenueGrowth }}% growth</span>
                    @elseif($revenueGrowth < 0)
                        <span class="text-danger"><i class="fas fa-arrow-down"></i> {{ abs($revenueGrowth) }}% decline</span>
                    @else
                        <span class="text-muted">No change</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Commission Earned</p>
                <h3 class="mb-0 text-primary">₹{{ number_format($stats['total_commission']) }}</h3>
                <small class="text-muted">From all won deals</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-1">Total Properties Listed</p>
                <h3 class="mb-0 text-warning">{{ $stats['total_properties'] }}</h3>
                <small class="text-muted">{{ $stats['total_clients'] }} total clients</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Monthly Revenue Chart --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Monthly Revenue (Last 12 Months)</h3>
                <a href="{{ route('crm.reports.export.deals') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-file-excel me-1"></i> Export Deals
                </a>
            </div>
            <div class="card-body">
                <canvas id="monthlyRevenueChart" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Leads by Source --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Leads by Source</h3>
                <a href="{{ route('crm.reports.export.leads') }}" class="btn btn-sm btn-info">
                    <i class="fas fa-file-excel me-1"></i> Export
                </a>
            </div>
            <div class="card-body">
                <canvas id="leadSourceChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Deals by Stage --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Deals by Stage</h3></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Stage</th><th>Count</th><th>Total Value</th></tr>
                    </thead>
                    <tbody>
                        @foreach($dealsByStage as $item)
                        @php
                            $stageColors = ['new'=>'secondary','site_visit'=>'info','negotiation'=>'warning','docs_pending'=>'primary','won'=>'success','lost'=>'danger'];
                        @endphp
                        <tr>
                            <td>
                                <span class="badge bg-{{ $stageColors[$item->stage] ?? 'secondary' }}">
                                    {{ config('crm.pipeline_stages')[$item->stage] ?? $item->stage }}
                                </span>
                            </td>
                            <td>{{ $item->count }}</td>
                            <td>₹{{ number_format($item->total_value) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    {{-- Top Agents --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Top Agents by Revenue</h3></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Agent</th><th>Deals Won</th><th>Revenue</th></tr>
                    </thead>
                    <tbody>
                        @forelse($topAgents as $item)
                        <tr>
                            <td>{{ $item->assignedAgent->name ?? '—' }}</td>
                            <td><span class="badge bg-success">{{ $item->deals_count }}</span></td>
                            <td><strong>₹{{ number_format($item->total_value) }}</strong></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No won deals yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Links --}}
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Detailed Reports</h3></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('crm.reports.leads') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fas fa-user-clock fa-2x d-block mb-2"></i>
                            Leads Report
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('crm.reports.deals') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fas fa-handshake fa-2x d-block mb-2"></i>
                            Deals Report
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('crm.reports.properties') }}" class="btn btn-outline-warning w-100 py-3">
                            <i class="fas fa-building fa-2x d-block mb-2"></i>
                            Properties Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page_js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Revenue Chart
const revenueData = @json($monthlyRevenue);
const monthNames  = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const revLabels   = revenueData.map(d => monthNames[d.month - 1] + ' ' + d.year);
const revValues   = revenueData.map(d => d.total);

new Chart(document.getElementById('monthlyRevenueChart'), {
    type: 'bar',
    data: {
        labels: revLabels,
        datasets: [{
            label: 'Revenue (INR)',
            data: revValues,
            backgroundColor: 'rgba(40,167,69,0.7)',
            borderColor: '#28a745',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: val => '₹' + (val/100000).toFixed(1) + 'L'
                }
            }
        }
    }
});

// Lead Sources Pie Chart
const sourcesData  = @json($leadsBySource);
const sourceLabels = sourcesData.map(s => s.source);
const sourceCounts = sourcesData.map(s => s.count);

new Chart(document.getElementById('leadSourceChart'), {
    type: 'doughnut',
    data: {
        labels: sourceLabels,
        datasets: [{
            data: sourceCounts,
            backgroundColor: ['#007bff','#28a745','#ffc107','#17a2b8','#dc3545','#6f42c1'],
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
@endsection