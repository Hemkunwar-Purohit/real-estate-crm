@extends('crm.layouts.app')
@section('page_title', 'Site Visits')

@section('page_actions')
    <a href="{{ route('crm.site-visits.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Schedule Visit
    </a>
@endsection

@section('main_content')

{{-- Stats --}}
<div class="row mb-3">
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-info"><i class="fas fa-calendar-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Upcoming</span>
                <span class="info-box-number">{{ $upcomingCount }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Completed Today</span>
                <span class="info-box-number">
                    {{ \App\Models\SiteVisit::where('status','completed')->whereDate('visit_datetime', today())->count() }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="scheduled"   {{ request('status') == 'scheduled'   ? 'selected':'' }}>Scheduled</option>
                        <option value="completed"   {{ request('status') == 'completed'   ? 'selected':'' }}>Completed</option>
                        <option value="cancelled"   {{ request('status') == 'cancelled'   ? 'selected':'' }}>Cancelled</option>
                        <option value="rescheduled" {{ request('status') == 'rescheduled' ? 'selected':'' }}>Rescheduled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="agent_id" class="form-select form-select-sm">
                        <option value="">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected':'' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check ms-2">
                        <input class="form-check-input" type="checkbox" name="today" value="1"
                            id="todayCheck" {{ request('today') ? 'checked':'' }}>
                        <label class="form-check-label" for="todayCheck">Today only</label>
                    </div>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    <a href="{{ route('crm.site-visits.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Site Visits <span class="badge bg-primary ms-2">{{ $visits->total() }}</span></h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Lead</th>
                    <th>Property</th>
                    <th>Agent</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Interest</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $visit)
                @php
                    $statusColors = [
                        'scheduled'   => 'primary',
                        'completed'   => 'success',
                        'cancelled'   => 'danger',
                        'rescheduled' => 'warning',
                    ];
                    $interestColors = [
                        'high'           => 'success',
                        'medium'         => 'warning',
                        'low'            => 'secondary',
                        'not_interested' => 'danger',
                    ];
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('crm.leads.show', $visit->lead) }}" class="fw-bold text-dark">
                            {{ $visit->lead->name }}
                        </a>
                        <br><small class="text-muted">{{ $visit->lead->phone }}</small>
                    </td>
                    <td>
                        <a href="{{ route('crm.properties.show', $visit->property) }}">
                            {{ Str::limit($visit->property->title, 25) }}
                        </a>
                        <br><small class="text-muted">{{ $visit->property->city }}</small>
                    </td>
                    <td>{{ $visit->agent->name }}</td>
                    <td>
                        <strong>{{ $visit->visit_datetime->format('d M Y') }}</strong>
                        <br><small class="text-muted">{{ $visit->visit_datetime->format('h:i A') }}</small>
                        @if($visit->visit_datetime->isToday())
                            <span class="badge bg-warning ms-1">Today</span>
                        @elseif($visit->visit_datetime->isFuture())
                            <span class="badge bg-info ms-1">Upcoming</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $statusColors[$visit->status] ?? 'secondary' }}">
                            {{ ucfirst($visit->status) }}
                        </span>
                    </td>
                    <td>
                        @if($visit->interest_level)
                            <span class="badge bg-{{ $interestColors[$visit->interest_level] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $visit->interest_level)) }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('crm.site-visits.show', $visit) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($visit->status == 'scheduled')
                            <a href="{{ route('crm.site-visits.edit', $visit) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endif
                            <form method="POST" action="{{ route('crm.site-visits.destroy', $visit) }}"
                                  onsubmit="return confirm('Delete this visit?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-map-marker-alt fa-2x mb-2 d-block"></i>
                        No site visits scheduled.
                        <a href="{{ route('crm.site-visits.create') }}">Schedule first visit</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
    <div class="card-footer">{{ $visits->links() }}</div>
</div>

@endsection