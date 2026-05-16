@extends('crm.layouts.app')

@section('page_title', $lead->name)

@section('page_actions')
    <div class="d-flex gap-2">
        @if(!$lead->is_converted)
        <form method="POST" action="{{ route('crm.leads.convert', $lead) }}"
              onsubmit="return confirm('Convert this lead to client?')">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success btn-sm">
                <i class="fas fa-user-check me-1"></i> Convert to Client
            </button>
        </form>
        @else
        <a href="{{ route('crm.clients.show', $lead->convertedClient) }}" class="btn btn-success btn-sm">
            <i class="fas fa-user me-1"></i> View Client
        </a>
        @endif
        <a href="{{ route('crm.leads.edit', $lead) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('crm.leads.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
@endsection

@section('main_content')

<div class="row">
    {{-- Lead Info --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <span class="display-1 text-muted">
                        <i class="fas fa-user-circle"></i>
                    </span>
                </div>
                <h4>{{ $lead->name }}</h4>
                <p class="text-muted mb-1"><i class="fas fa-phone me-1"></i> {{ $lead->phone }}</p>
                @if($lead->email)
                <p class="text-muted mb-1"><i class="fas fa-envelope me-1"></i> {{ $lead->email }}</p>
                @endif
                <span class="badge bg-{{ $lead->is_converted ? 'success' : 'primary' }} mt-2">
                    {{ $lead->is_converted ? 'Converted' : ucfirst($lead->status) }}
                </span>
            </div>
            <div class="card-footer p-0">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Source</td>
                        <td>{{ config('crm.lead_sources')[$lead->source] ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Looking to</td>
                        <td><span class="badge bg-info">{{ ucfirst($lead->listing_type) }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Property</td>
                        <td>{{ config('crm.property_types')[$lead->property_type] ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Budget</td>
                        <td>
                            @if($lead->budget_min || $lead->budget_max)
                                ₹{{ number_format($lead->budget_min) }} — ₹{{ number_format($lead->budget_max) }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">City</td>
                        <td>{{ $lead->preferred_city ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Locality</td>
                        <td>{{ $lead->preferred_locality ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Agent</td>
                        <td>{{ $lead->assignedAgent->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Added</td>
                        <td>{{ $lead->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Requirements --}}
        @if($lead->requirements)
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Requirements</h6></div>
            <div class="card-body">
                <p class="mb-0">{{ $lead->requirements }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- Activities & Site Visits --}}
    <div class="col-md-8">

        {{-- Site Visits --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Site Visits</h6>
                <a href="{{ route('crm.site-visits.create') }}?lead_id={{ $lead->id }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> Schedule Visit
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Property</th><th>Date</th><th>Status</th><th>Feedback</th></tr></thead>
                    <tbody>
                        @forelse($lead->siteVisits as $visit)
                        <tr>
                            <td>{{ $visit->property->title ?? '—' }}</td>
                            <td>{{ $visit->visit_datetime->format('d M, h:i A') }}</td>
                            <td>
                                <span class="badge bg-{{ $visit->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($visit->status) }}
                                </span>
                            </td>
                            <td>{{ $visit->interest_level ? ucfirst($visit->interest_level) : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-2">No site visits yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Activity Timeline --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-history me-2"></i>Activity Timeline</h6></div>
            <div class="card-body">
                @forelse($lead->activities as $activity)
                <div class="d-flex gap-3 mb-3">
                    <div>
                        <span class="badge bg-secondary rounded-pill p-2">
                            <i class="fas fa-{{ $activity->type == 'call' ? 'phone' : ($activity->type == 'email' ? 'envelope' : 'sticky-note') }}"></i>
                        </span>
                    </div>
                    <div>
                        <strong>{{ ucfirst($activity->type) }}</strong>
                        <span class="text-muted ms-2">by {{ $activity->user->name }}</span>
                        <br>
                        <span>{{ $activity->description }}</span>
                        <br>
                        <small class="text-muted">{{ $activity->activity_date->format('d M Y, h:i A') }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center mb-0">No activities logged yet</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection