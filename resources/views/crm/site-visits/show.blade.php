@extends('crm.layouts.app')
@section('page_title', 'Site Visit Detail')

@section('page_actions')
    <div class="d-flex gap-2">
        @if($siteVisit->status == 'scheduled')
        <a href="{{ route('crm.site-visits.edit', $siteVisit) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        @endif
        <a href="{{ route('crm.site-visits.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
@endsection

@section('main_content')
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Visit Details</h6></div>
            <div class="card-body p-0">
                @php
                    $statusColors = ['scheduled'=>'primary','completed'=>'success','cancelled'=>'danger','rescheduled'=>'warning'];
                    $interestColors = ['high'=>'success','medium'=>'warning','low'=>'secondary','not_interested'=>'danger'];
                @endphp
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Lead</td>
                        <td>
                            <a href="{{ route('crm.leads.show', $siteVisit->lead) }}">
                                {{ $siteVisit->lead->name }}
                            </a>
                            <br><small>{{ $siteVisit->lead->phone }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Property</td>
                        <td>
                            <a href="{{ route('crm.properties.show', $siteVisit->property) }}">
                                {{ $siteVisit->property->title }}
                            </a>
                            <br><small>{{ $siteVisit->property->city }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Agent</td>
                        <td>{{ $siteVisit->agent->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date & Time</td>
                        <td>
                            <strong>{{ $siteVisit->visit_datetime->format('d M Y') }}</strong><br>
                            {{ $siteVisit->visit_datetime->format('h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td>
                            <span class="badge bg-{{ $statusColors[$siteVisit->status] ?? 'secondary' }}">
                                {{ ucfirst($siteVisit->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Interest</td>
                        <td>
                            @if($siteVisit->interest_level)
                                <span class="badge bg-{{ $interestColors[$siteVisit->interest_level] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $siteVisit->interest_level)) }}
                                </span>
                            @else — @endif
                        </td>
                    </tr>
                    @if($siteVisit->feedback)
                    <tr>
                        <td class="text-muted">Feedback</td>
                        <td>{{ $siteVisit->feedback }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Mark Complete Form --}}
    @if($siteVisit->status == 'scheduled')
    <div class="col-md-7">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0"><i class="fas fa-check-circle me-2"></i>Mark Visit as Completed</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('crm.site-visits.complete', $siteVisit) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Interest Level</label>
                        <select name="interest_level" class="form-select">
                            <option value="">Select Interest Level</option>
                            <option value="high">High — Very Interested</option>
                            <option value="medium">Medium — Somewhat Interested</option>
                            <option value="low">Low — Not Very Interested</option>
                            <option value="not_interested">Not Interested</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Feedback / Notes</label>
                        <textarea name="feedback" class="form-control" rows="4"
                            placeholder="What did the lead say? Any specific requirements or objections?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Mark as Completed
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection