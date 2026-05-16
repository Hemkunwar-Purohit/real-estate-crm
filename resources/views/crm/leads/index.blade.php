@extends('crm.layouts.app')

@section('page_title', 'Leads')

@section('page_actions')
    <a href="{{ route('crm.leads.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Lead
    </a>
@endsection

@section('main_content')

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('crm.leads.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search name, phone, email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                        <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                        <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                        <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="source" class="form-select form-select-sm">
                        <option value="">All Sources</option>
                        @foreach(config('crm.lead_sources') as $key => $label)
                            <option value="{{ $key }}" {{ request('source') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="listing_type" class="form-select form-select-sm">
                        <option value="">Buy / Rent</option>
                        <option value="buy" {{ request('listing_type') == 'buy' ? 'selected' : '' }}>Buy</option>
                        <option value="rent" {{ request('listing_type') == 'rent' ? 'selected' : '' }}>Rent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="assigned_to" class="form-select form-select-sm">
                        <option value="">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('assigned_to') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('crm.leads.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Leads Table --}}
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            All Leads
            <span class="badge bg-primary ms-2">{{ $leads->total() }}</span>
        </h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Budget</th>
                    <th>Source</th>
                    <th>Status</th>
                    <th>Agent</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leads as $lead)
                <tr class="{{ $lead->is_converted ? 'table-success' : '' }}">
                    <td>{{ $loop->iteration + ($leads->currentPage() - 1) * $leads->perPage() }}</td>
                    <td>
                        <a href="{{ route('crm.leads.show', $lead) }}" class="fw-bold text-dark">
                            {{ $lead->name }}
                        </a>
                        @if($lead->is_converted)
                            <span class="badge bg-success ms-1">Converted</span>
                        @endif
                        @if($lead->preferred_city)
                            <br><small class="text-muted"><i class="fas fa-map-marker-alt"></i> {{ $lead->preferred_city }}</small>
                        @endif
                    </td>
                    <td>
                        <a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a>
                    </td>
                    <td>
                        <span class="badge bg-{{ $lead->listing_type == 'buy' ? 'primary' : 'info' }}">
                            {{ ucfirst($lead->listing_type) }}
                        </span>
                    </td>
                    <td>
                        @if($lead->budget_min || $lead->budget_max)
                            <small>
                                {{ $lead->budget_min ? number_format($lead->budget_min) : '?' }}
                                —
                                {{ $lead->budget_max ? number_format($lead->budget_max) : '?' }}
                            </small>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        {{ config('crm.lead_sources')[$lead->source] ?? $lead->source ?? '—' }}
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'new'       => 'secondary',
                                'contacted' => 'info',
                                'qualified' => 'primary',
                                'converted' => 'success',
                                'lost'      => 'danger',
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$lead->status] ?? 'secondary' }}">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </td>
                    <td>{{ $lead->assignedAgent->name ?? '—' }}</td>
                    <td><small>{{ $lead->created_at->format('d M Y') }}</small></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('crm.leads.show', $lead) }}"
                               class="btn btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('crm.leads.edit', $lead) }}"
                               class="btn btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(!$lead->is_converted)
                            <form method="POST" action="{{ route('crm.leads.convert', $lead) }}"
                                  onsubmit="return confirm('Convert this lead to client?')">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-outline-success" title="Convert to Client">
                                    <i class="fas fa-user-check"></i>
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('crm.leads.destroy', $lead) }}"
                                  onsubmit="return confirm('Delete this lead?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-4">
                        <i class="fas fa-user-clock fa-2x mb-2 d-block"></i>
                        No leads found.
                        <a href="{{ route('crm.leads.create') }}">Add your first lead</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $leads->links() }}
    </div>
</div>

@endsection