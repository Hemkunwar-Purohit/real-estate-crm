@extends('crm.layouts.app')
@section('page_title', 'Edit Deal')
@section('page_actions')
    <a href="{{ route('crm.deals.show', $deal) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.deals.update', $deal) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Deal Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $deal->title) }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Client</label>
                            <select name="client_id" class="form-select">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $deal->client_id) == $client->id ? 'selected':'' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Property</label>
                            <select name="property_id" class="form-select">
                                <option value="">No Property</option>
                                @foreach($properties as $property)
                                    <option value="{{ $property->id }}" {{ old('property_id', $deal->property_id) == $property->id ? 'selected':'' }}>
                                        {{ $property->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deal Value</label>
                            <div class="input-group">
                                <select name="currency" class="form-select" style="max-width:90px">
                                    @foreach(config('crm.currencies') as $c)
                                        <option value="{{ $c }}" {{ old('currency', $deal->currency) == $c ? 'selected':'' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="deal_value" class="form-control" value="{{ old('deal_value', $deal->deal_value) }}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Commission</label>
                            <input type="number" name="commission" class="form-control" value="{{ old('commission', $deal->commission) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stage</label>
                            <select name="stage" class="form-select">
                                @foreach(config('crm.pipeline_stages') as $key => $label)
                                    <option value="{{ $key }}" {{ old('stage', $deal->stage) == $key ? 'selected':'' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expected Close Date</label>
                            <input type="date" name="expected_close_date" class="form-control"
                                value="{{ old('expected_close_date', $deal->expected_close_date?->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $deal->notes) }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select name="assigned_to" class="form-select">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('assigned_to', $deal->assigned_to) == $agent->id ? 'selected':'' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Linked Lead</label>
                        <select name="lead_id" class="form-select">
                            <option value="">No Lead</option>
                            @foreach($leads as $lead)
                                <option value="{{ $lead->id }}" {{ old('lead_id', $deal->lead_id) == $lead->id ? 'selected':'' }}>
                                    {{ $lead->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Deal
                </button>
                <a href="{{ route('crm.deals.show', $deal) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection