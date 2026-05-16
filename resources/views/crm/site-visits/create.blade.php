@extends('crm.layouts.app')
@section('page_title', 'Schedule Site Visit')
@section('page_actions')
    <a href="{{ route('crm.site-visits.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.site-visits.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Lead <span class="text-danger">*</span></label>
                        <select name="lead_id" class="form-select @error('lead_id') is-invalid @enderror">
                            <option value="">Select Lead</option>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}"
                                    {{ old('lead_id', $selectedLead) == $lead->id ? 'selected':'' }}>
                                    {{ $lead->name }} — {{ $lead->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('lead_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Property <span class="text-danger">*</span></label>
                        <select name="property_id" class="form-select @error('property_id') is-invalid @enderror">
                            <option value="">Select Property</option>
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}"
                                    {{ old('property_id', $selectedProperty) == $property->id ? 'selected':'' }}>
                                    {{ $property->title }} — {{ $property->city }}
                                </option>
                            @endforeach
                        </select>
                        @error('property_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Agent <span class="text-danger">*</span></label>
                        <select name="agent_id" class="form-select @error('agent_id') is-invalid @enderror">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}"
                                    {{ old('agent_id', auth()->id()) == $agent->id ? 'selected':'' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('agent_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Visit Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="visit_datetime"
                            class="form-control @error('visit_datetime') is-invalid @enderror"
                            value="{{ old('visit_datetime') }}"
                            min="{{ now()->format('Y-m-d\TH:i') }}">
                        @error('visit_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-calendar-plus me-1"></i> Schedule Visit
                </button>
                <a href="{{ route('crm.site-visits.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection