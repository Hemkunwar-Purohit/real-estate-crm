@extends('crm.layouts.app')

@section('page_title', 'Add New Lead')

@section('page_actions')
    <a href="{{ route('crm.leads.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.leads.store') }}">
            @csrf

            <div class="row">
                {{-- Basic Info --}}
                <div class="col-md-6">
                    <h6 class="text-muted fw-bold mb-3">BASIC INFORMATION</h6>

                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Lead full name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}" placeholder="+91 98765 43210">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="email@example.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-select">
                                <option value="">Select Source</option>
                                @foreach(config('crm.lead_sources') as $key => $label)
                                    <option value="{{ $key }}" {{ old('source') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="new">New</option>
                                <option value="contacted">Contacted</option>
                                <option value="qualified">Qualified</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Property Requirements --}}
                <div class="col-md-6">
                    <h6 class="text-muted fw-bold mb-3">PROPERTY REQUIREMENTS</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Looking to <span class="text-danger">*</span></label>
                            <select name="listing_type" class="form-select @error('listing_type') is-invalid @enderror">
                                <option value="buy" {{ old('listing_type') == 'buy' ? 'selected' : '' }}>Buy</option>
                                <option value="rent" {{ old('listing_type') == 'rent' ? 'selected' : '' }}>Rent</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Property Type</label>
                            <select name="property_type" class="form-select">
                                <option value="">Any Type</option>
                                @foreach(config('crm.property_types') as $key => $label)
                                    <option value="{{ $key }}" {{ old('property_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Budget Min (INR)</label>
                            <input type="number" name="budget_min" class="form-control"
                                value="{{ old('budget_min') }}" placeholder="e.g. 2000000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Budget Max (INR)</label>
                            <input type="number" name="budget_max" class="form-control"
                                value="{{ old('budget_max') }}" placeholder="e.g. 5000000">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preferred City</label>
                            <input type="text" name="preferred_city" class="form-control"
                                value="{{ old('preferred_city') }}" placeholder="e.g. Mumbai">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Preferred Locality</label>
                            <input type="text" name="preferred_locality" class="form-control"
                                value="{{ old('preferred_locality') }}" placeholder="e.g. Bandra">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Assign To Agent</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('assigned_to') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Requirements --}}
                <div class="col-12 mb-3">
                    <label class="form-label">Additional Requirements</label>
                    <textarea name="requirements" class="form-control" rows="3"
                        placeholder="Any specific requirements...">{{ old('requirements') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Lead
                </button>
                <a href="{{ route('crm.leads.index') }}" class="btn btn-secondary">Cancel</a>
            </div>

        </form>
    </div>
</div>

@endsection