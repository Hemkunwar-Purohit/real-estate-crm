@extends('crm.layouts.app')
@section('page_title', 'Edit Site Visit')
@section('page_actions')
    <a href="{{ route('crm.site-visits.show', $siteVisit) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.site-visits.update', $siteVisit) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Lead</label>
                        <select name="lead_id" class="form-select">
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}" {{ $siteVisit->lead_id == $lead->id ? 'selected':'' }}>
                                    {{ $lead->name }} — {{ $lead->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Property</label>
                        <select name="property_id" class="form-select">
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" {{ $siteVisit->property_id == $property->id ? 'selected':'' }}>
                                    {{ $property->title }} — {{ $property->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Agent</label>
                        <select name="agent_id" class="form-select">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ $siteVisit->agent_id == $agent->id ? 'selected':'' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Visit Date & Time</label>
                        <input type="datetime-local" name="visit_datetime" class="form-control"
                            value="{{ $siteVisit->visit_datetime->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="scheduled"   {{ $siteVisit->status == 'scheduled'   ? 'selected':'' }}>Scheduled</option>
                            <option value="completed"   {{ $siteVisit->status == 'completed'   ? 'selected':'' }}>Completed</option>
                            <option value="cancelled"   {{ $siteVisit->status == 'cancelled'   ? 'selected':'' }}>Cancelled</option>
                            <option value="rescheduled" {{ $siteVisit->status == 'rescheduled' ? 'selected':'' }}>Rescheduled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Interest Level</label>
                        <select name="interest_level" class="form-select">
                            <option value="">Not assessed</option>
                            <option value="high"           {{ $siteVisit->interest_level == 'high'           ? 'selected':'' }}>High</option>
                            <option value="medium"         {{ $siteVisit->interest_level == 'medium'         ? 'selected':'' }}>Medium</option>
                            <option value="low"            {{ $siteVisit->interest_level == 'low'            ? 'selected':'' }}>Low</option>
                            <option value="not_interested" {{ $siteVisit->interest_level == 'not_interested' ? 'selected':'' }}>Not Interested</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Feedback</label>
                    <textarea name="feedback" class="form-control" rows="3">{{ $siteVisit->feedback }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Visit
                </button>
                <a href="{{ route('crm.site-visits.show', $siteVisit) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection