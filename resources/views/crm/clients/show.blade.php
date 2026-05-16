@extends('crm.layouts.app')

@section('page_title', $client->name)

@section('page_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.clients.edit', $client) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('crm.clients.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
@endsection

@section('main_content')

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <span class="display-1 text-muted"><i class="fas fa-user-circle"></i></span>
                <h4 class="mt-2">{{ $client->name }}</h4>
                @php
                    $typeColors = ['buyer'=>'primary','seller'=>'success','tenant'=>'info','landlord'=>'warning'];
                @endphp
                <span class="badge bg-{{ $typeColors[$client->type] ?? 'secondary' }}">
                    {{ ucfirst($client->type) }}
                </span>
            </div>
            <div class="card-footer p-0">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted">Phone</td>
                        <td><a href="tel:{{ $client->phone }}">{{ $client->phone }}</a></td>
                    </tr>
                    @if($client->alternate_phone)
                    <tr>
                        <td class="text-muted">Alt Phone</td>
                        <td>{{ $client->alternate_phone }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Email</td>
                        <td>{{ $client->email ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">City</td>
                        <td>{{ $client->city ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Source</td>
                        <td>{{ config('crm.lead_sources')[$client->source] ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Agent</td>
                        <td>{{ $client->assignedAgent->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Since</td>
                        <td>{{ $client->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($client->notes)
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Notes</h6></div>
            <div class="card-body"><p class="mb-0">{{ $client->notes }}</p></div>
        </div>
        @endif
    </div>

    <div class="col-md-8">

        {{-- Deals --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Deals</h6>
                <a href="{{ route('crm.deals.create') }}?client_id={{ $client->id }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> New Deal
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Deal</th><th>Property</th><th>Value</th><th>Stage</th></tr>
                    </thead>
                    <tbody>
                        @forelse($client->deals as $deal)
                        <tr>
                            <td>
                                <a href="{{ route('crm.deals.show', $deal) }}">{{ $deal->title }}</a>
                            </td>
                            <td>{{ $deal->property ? Str::limit($deal->property->title, 25) : '—' }}</td>
                            <td>{{ $deal->currency }} {{ number_format($deal->deal_value) }}</td>
                            <td>
                                @php
                                    $stageColors = ['new'=>'secondary','site_visit'=>'info','negotiation'=>'warning','docs_pending'=>'primary','won'=>'success','lost'=>'danger'];
                                @endphp
                                <span class="badge bg-{{ $stageColors[$deal->stage] ?? 'secondary' }}">
                                    {{ config('crm.pipeline_stages')[$deal->stage] ?? $deal->stage }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-2">No deals yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Properties owned --}}
        @if($client->type == 'seller' || $client->type == 'landlord')
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Properties</h6>
                <a href="{{ route('crm.properties.create') }}?owner_id={{ $client->id }}"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> Add Property
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr><th>Title</th><th>Type</th><th>Price</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($client->properties as $property)
                        <tr>
                            <td>
                                <a href="{{ route('crm.properties.show', $property) }}">
                                    {{ $property->title }}
                                </a>
                            </td>
                            <td>{{ config('crm.property_types')[$property->property_type] ?? '—' }}</td>
                            <td>{{ $property->formatted_price }}</td>
                            <td>
                                <span class="badge bg-{{ $property->status == 'available' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($property->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-2">No properties listed</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection