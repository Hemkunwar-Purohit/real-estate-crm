@extends('crm.layouts.app')
@section('page_title', 'Leads Report')

@section('page_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.reports.export.leads', request()->query()) }}"
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
            <div class="inner"><h3>{{ $summary['total'] }}</h3><p>Total Leads</p></div>
            <div class="icon"><i class="fas fa-users"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-success">
            <div class="inner"><h3>{{ $summary['converted'] }}</h3><p>Converted</p></div>
            <div class="icon"><i class="fas fa-user-check"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner"><h3>{{ $summary['new'] }}</h3><p>New</p></div>
            <div class="icon"><i class="fas fa-user-plus"></i></div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner"><h3>{{ $summary['qualified'] }}</h3><p>Qualified</p></div>
            <div class="icon"><i class="fas fa-star"></i></div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        @foreach(['new','contacted','qualified','converted','lost'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="source" class="form-select form-select-sm">
                        <option value="">All Sources</option>
                        @foreach(config('crm.lead_sources') as $key => $label)
                            <option value="{{ $key }}" {{ request('source') == $key ? 'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from_date" class="form-control form-control-sm"
                        value="{{ request('from_date') }}" placeholder="From date">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to_date" class="form-control form-control-sm"
                        value="{{ request('to_date') }}" placeholder="To date">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    <a href="{{ route('crm.reports.leads') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Leads <span class="badge bg-primary ms-2">{{ $leads->total() }}</span></h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Budget</th>
                    <th>City</th>
                    <th>Agent</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                @php
                    $statusColors = ['new'=>'secondary','contacted'=>'info','qualified'=>'primary','converted'=>'success','lost'=>'danger'];
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('crm.leads.show', $lead) }}">{{ $lead->name }}</a>
                        @if($lead->is_converted)<span class="badge bg-success ms-1">Converted</span>@endif
                    </td>
                    <td>{{ $lead->phone }}</td>
                    <td>{{ config('crm.lead_sources')[$lead->source] ?? '—' }}</td>
                    <td><span class="badge bg-{{ $statusColors[$lead->status] ?? 'secondary' }}">{{ ucfirst($lead->status) }}</span></td>
                    <td>{{ ucfirst($lead->listing_type) }}</td>
                    <td>
                        @if($lead->budget_max)
                            ₹{{ number_format($lead->budget_max) }}
                        @else — @endif
                    </td>
                    <td>{{ $lead->preferred_city ?? '—' }}</td>
                    <td>{{ $lead->assignedAgent->name ?? '—' }}</td>
                    <td><small>{{ $lead->created_at->format('d M Y') }}</small></td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center text-muted py-4">No leads found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $leads->links() }}</div>
</div>

@endsection