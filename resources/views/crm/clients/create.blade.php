@extends('crm.layouts.app')
@section('page_title', 'Add Client')
@section('page_actions')
    <a href="{{ route('crm.clients.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.clients.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted fw-bold mb-3">BASIC INFORMATION</h6>

                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="Client full name">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone') }}" placeholder="+91 98765 43210">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alternate Phone</label>
                        <input type="text" name="alternate_phone" class="form-control"
                            value="{{ old('alternate_phone') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="text-muted fw-bold mb-3">OTHER DETAILS</h6>

                    <div class="mb-3">
                        <label class="form-label">Client Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            <option value="buyer"    {{ old('type') == 'buyer'    ? 'selected' : '' }}>Buyer</option>
                            <option value="seller"   {{ old('type') == 'seller'   ? 'selected' : '' }}>Seller</option>
                            <option value="tenant"   {{ old('type') == 'tenant'   ? 'selected' : '' }}>Tenant</option>
                            <option value="landlord" {{ old('type') == 'landlord' ? 'selected' : '' }}>Landlord</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                            value="{{ old('city') }}" placeholder="e.g. Mumbai">
                    </div>

                    <div class="mb-3">
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

                <div class="col-12 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control"
                        value="{{ old('address') }}" placeholder="Full address">
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3"
                        placeholder="Any notes about this client...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Client
                </button>
                <a href="{{ route('crm.clients.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection