@extends('crm.layouts.app')
@section('page_title', $deal->title)

@section('page_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.deals.edit', $deal) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('crm.deals.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
@endsection

@section('main_content')

{{-- Pipeline progress bar --}}
@php
    $stages    = array_keys(config('crm.pipeline_stages'));
    $currentIdx = array_search($deal->stage, $stages);
    $progress   = $currentIdx !== false ? (($currentIdx + 1) / count($stages)) * 100 : 0;
    $stageColors = ['new'=>'secondary','site_visit'=>'info','negotiation'=>'warning','docs_pending'=>'primary','won'=>'success','lost'=>'danger'];
@endphp

<div class="card mb-3">
    <div class="card-body py-2">
        <div class="d-flex justify-content-between mb-1">
            @foreach(config('crm.pipeline_stages') as $key => $label)
            <small class="text-{{ $deal->stage == $key ? $stageColors[$key] : 'muted' }} fw-{{ $deal->stage == $key ? 'bold' : 'normal' }}">
                {{ $label }}
            </small>
            @endforeach
        </div>
        <div class="progress" style="height:8px;">
            <div class="progress-bar bg-{{ $stageColors[$deal->stage] ?? 'primary' }}"
                 style="width: {{ $progress }}%"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Deal Details</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Value</td>
                        <td><strong class="text-success fs-5">{{ $deal->currency }} {{ number_format($deal->deal_value) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Commission</td>
                        <td>{{ $deal->commission ? $deal->currency . ' ' . number_format($deal->commission) : '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Stage</td>
                        <td>
                            <span class="badge bg-{{ $stageColors[$deal->stage] ?? 'secondary' }}">
                                {{ config('crm.pipeline_stages')[$deal->stage] ?? $deal->stage }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Client</td>
                        <td>
                            <a href="{{ route('crm.clients.show', $deal->client) }}">
                                {{ $deal->client->name }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Property</td>
                        <td>
                            @if($deal->property)
                                <a href="{{ route('crm.properties.show', $deal->property) }}">
                                    {{ Str::limit($deal->property->title, 25) }}
                                </a>
                            @else — @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Agent</td>
                        <td>{{ $deal->assignedAgent->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Expected Close</td>
                        <td>{{ $deal->expected_close_date ? $deal->expected_close_date->format('d M Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Actual Close</td>
                        <td>{{ $deal->actual_close_date ? $deal->actual_close_date->format('d M Y') : '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Quick stage change --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Update Stage</h6></div>
            <div class="card-body">
                <form method="POST" action="{{ route('crm.deals.stage', $deal) }}">
                    @csrf @method('PATCH')
                    <div class="d-flex gap-2">
                        <select name="stage" class="form-select form-select-sm">
                            @foreach(config('crm.pipeline_stages') as $key => $label)
                                <option value="{{ $key }}" {{ $deal->stage == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>

        @if($deal->notes)
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Notes</h6></div>
            <div class="card-body"><p class="mb-0">{{ $deal->notes }}</p></div>
        </div>
        @endif
    </div>

    <div class="col-md-8">
        {{-- Activity Timeline --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-history me-2"></i>Activity Timeline</h6></div>
            <div class="card-body">
                @forelse($deal->activities as $activity)
                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                    <span class="badge bg-secondary rounded-pill p-2 align-self-start">
                        <i class="fas fa-{{ $activity->type == 'call' ? 'phone' : ($activity->type == 'email' ? 'envelope' : 'sticky-note') }}"></i>
                    </span>
                    <div>
                        <strong>{{ ucfirst($activity->type) }}</strong>
                        <span class="text-muted ms-2">by {{ $activity->user->name }}</span>
                        <br>{{ $activity->description }}
                        <br><small class="text-muted">{{ $activity->activity_date->format('d M Y, h:i A') }}</small>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center mb-0">No activities logged yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection