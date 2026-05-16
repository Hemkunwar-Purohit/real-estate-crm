@extends('crm.layouts.app')
@section('page_title', 'Create Deal')
@section('page_actions')
    <a href="{{ route('crm.deals.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.deals.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-muted fw-bold mb-3">DEAL INFORMATION</h6>

                    <div class="mb-3">
                        <label class="form-label">Deal Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="e.g. 3BHK Sale — Rahul Sharma">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client <span class="text-danger">*</span></label>
                            <select name="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ old('client_id', $selectedClient) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ ucfirst($client->type) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Property</label>
                            <select name="property_id" class="form-select">
                                <option value="">Select Property</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id') == $property->id ? 'selected' : '' }}>
                                        {{ $property->title }} — {{ $property->city }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deal Value <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="currency" class="form-select" style="max-width:90px">
                                    @foreach(config('crm.currencies') as $c)
                                        <option value="{{ $c }}" {{ old('currency','INR') == $c ? 'selected':'' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="deal_value" class="form-control @error('deal_value') is-invalid @enderror"
                                    value="{{ old('deal_value') }}" placeholder="e.g. 5000000">
                            </div>
                            @error('deal_value')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Commission</label>
                            <input type="number" name="commission" class="form-control"
                                value="{{ old('commission') }}" placeholder="Agent commission amount">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pipeline Stage <span class="text-danger">*</span></label>
                            <select name="stage" class="form-select @error('stage') is-invalid @enderror">
                                @foreach(config('crm.pipeline_stages') as $key => $label)
                                    <option value="{{ $key }}" {{ old('stage','new') == $key ? 'selected':'' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expected Close Date</label>
                            <input type="date" name="expected_close_date" class="form-control"
                                value="{{ old('expected_close_date') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Deal notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <h6 class="text-muted fw-bold mb-3">ASSIGNMENT</h6>

                    <div class="mb-3">
                        <label class="form-label">Assign To <span class="text-danger">*</span></label>
                        <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('assigned_to', auth()->id()) == $agent->id ? 'selected':'' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Linked Lead</label>
                        <select name="lead_id" class="form-select">
                            <option value="">Select Lead (optional)</option>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}"
                                    {{ old('lead_id', $selectedLead) == $lead->id ? 'selected':'' }}>
                                    {{ $lead->name }} — {{ $lead->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Quick summary card --}}
                    <div class="card bg-light mt-4">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Pipeline Stages</h6>
                            @foreach(config('crm.pipeline_stages') as $key => $label)
                            <div class="d-flex align-items-center gap-2 mb-1">
                                @php
                                    $colors = ['new'=>'secondary','site_visit'=>'info','negotiation'=>'warning','docs_pending'=>'primary','won'=>'success','lost'=>'danger'];
                                @endphp
                                <span class="badge bg-{{ $colors[$key] ?? 'secondary' }}" style="width:80px">{{ $label }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Create Deal
                </button>
                <a href="{{ route('crm.deals.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection