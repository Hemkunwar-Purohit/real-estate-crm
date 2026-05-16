@extends('crm.layouts.app')

@section('page_title', 'Edit Lead: ' . $lead->name)

@section('page_actions')
    <a href="{{ route('crm.leads.show', $lead) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.leads.update', $lead) }}">
            @csrf @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted fw-bold mb-3">BASIC INFORMATION</h6>

                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $lead->name) }}">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', $lead->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $lead->email) }}">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-select">
                                <option value="">Select Source</option>
                                @foreach(config('crm.lead_sources') as $key => $label)
                                    <option value="{{ $key }}" {{ old('source', $lead->source) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(['new','contacted','qualified','lost'] as $s)
                                <option value="{{ $s }}" {{ old('status', $lead->status) == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted fw-bold mb-3">PROPERTY REQUIREMENTS</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Looking to</label>
                            <select name="listing_type" class="form-select">
                                <option value="buy" {{ old('listing_type', $lead->listing_type) == 'buy' ? 'selected' : '' }}>Buy</option>
                                <option value="rent" {{ old('listing_type', $lead->listing_type) == 'rent' ? 'selected' : '' }}>Rent</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Property Type</label>
                            <select name="property_type" class="form-select">
                                <option value="">Any</option>
                                @foreach(config('crm.property_types') as $key => $label)
                                    <option value="{{ $key }}" {{ old('property_type', $lead->property_type) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Budget Min</label>
                            <input type="number" name="budget_min" class="form-control"
                                value="{{ old('budget_min', $lead->budget_min) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Budget Max</label>
                            <input type="number" name="budget_max" class="form-control"
                                value="{{ old('budget_max', $lead->budget_max) }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preferred City</label>
                            <input type="text" name="preferred_city" class="form-control"
                                value="{{ old('preferred_city', $lead->preferred_city) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preferred Locality</label>
                            <input type="text" name="preferred_locality" class="form-control"
                                value="{{ old('preferred_locality', $lead->preferred_locality) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assign To Agent</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('assigned_to', $lead->assigned_to) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Additional Requirements</label>
                    <textarea name="requirements" class="form-control" rows="3">{{ old('requirements', $lead->requirements) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Lead
                </button>
                <a href="{{ route('crm.leads.show', $lead) }}" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

@endsection