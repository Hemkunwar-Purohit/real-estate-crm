@extends('crm.layouts.app')
@section('page_title', 'Edit: ' . $client->name)
@section('page_actions')
    <a href="{{ route('crm.clients.show', $client) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.clients.update', $client) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $client->name) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', $client->phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alternate Phone</label>
                        <input type="text" name="alternate_phone" class="form-control"
                            value="{{ old('alternate_phone', $client->alternate_phone) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $client->email) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Client Type</label>
                        <select name="type" class="form-select">
                            @foreach(['buyer','seller','tenant','landlord'] as $t)
                            <option value="{{ $t }}" {{ old('type', $client->type) == $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                            value="{{ old('city', $client->city) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Source</label>
                        <select name="source" class="form-select">
                            <option value="">Select Source</option>
                            @foreach(config('crm.lead_sources') as $key => $label)
                                <option value="{{ $key }}" {{ old('source', $client->source) == $key ? 'selected' : '' }}>
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
                                <option value="{{ $agent->id }}" {{ old('assigned_to', $client->assigned_to) == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control"
                        value="{{ old('address', $client->address) }}">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $client->notes) }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Client
                </button>
                <a href="{{ route('crm.clients.show', $client) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection