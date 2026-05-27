@extends('crm.layouts.app')
@section('page_title', 'Deals Report')

@section('page_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.reports.export.deals', request()->query()) }}"
           class="btn btn-success btn-sm">
            <i class="fas fa-file-excel me-1"></i> Export Excel
        </a>
        <a href="{{ route('crm.reports.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
@endsection

@section('main_content')

{{-- Summary --}}
<div class="row mb-3">
    <div class="col-md-3 col-6">
        <div class="small-box bg-info">
            <div class="inner"><h3>{{ $summary['won_count'] }}</h3><p>Won Deals</p></div>
            <div class="icon"><i class="fas fa-trophy"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>₹{{ number_format($summary['won_value']/100000, 1) }}L</h3>
                <p>Won Revenue</p>
            </div>
            <div class="icon"><i class="fas fa-rupee-sign"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>₹{{ number_format($summary['total_value']/100000, 1) }}L</h3>
                <p>Pipeline Value</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>₹{{ number_format($summary['total_commission']) }}</h3>
                <p>Commission</p>
            </div>
            <div class="icon"><i class="fas fa-percentage"></i></div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <select name="stage" class="form-select form-select-sm">
                        <option value="">All Stages</option>
                        @foreach(config('crm.pipeline_stages') as $key => $label)
                            <option value="{{ $key }}" {{ request('stage') == $key ? 'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    <a href="{{ route('crm.reports.deals') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Deals <span class="badge bg-primary ms-2">{{ $deals->total() }}</span></h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Deal</th>
                    <th>Client</th>
                    <th>Value</th>
                    <th>Commission</th>
                    <th>Stage</th>
                    <th>Agent</th>
                    <th>Close Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deals as $deal)
                @php
                    $stageColors = ['new'=>'secondary','site_visit'=>'info','negotiation'=>'warning','docs_pending'=>'primary','won'=>'success','lost'=>'danger'];
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="{{ route('crm.deals.show', $deal) }}">{{ $deal->title }}</a></td>
                    <td>{{ $deal->client->name }}</td>
                    <td><strong class="text-success">₹{{ number_format($deal->deal_value) }}</strong></td>
                    <td>{{ $deal->commission ? '₹' . number_format($deal->commission) : '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $stageColors[$deal->stage] ?? 'secondary' }}">
                            {{ config('crm.pipeline_stages')[$deal->stage] ?? $deal->stage }}
                        </span>
                    </td>
                    <td>{{ $deal->assignedAgent->name }}</td>
                    <td>{{ $deal->actual_close_date ? $deal->actual_close_date->format('d M Y') : '—' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No deals found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $deals->links() }}</div>
</div>

@endsection