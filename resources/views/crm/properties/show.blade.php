@extends('crm.layouts.app')
@section('page_title', $property->title)
@section('page_actions')
    <div class="d-flex gap-2">
        <a href="{{ route('crm.properties.edit', $property) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('crm.properties.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
@endsection

@section('main_content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Property Details</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">Type</td><td>{{ config('crm.property_types')[$property->property_type] ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Listing</td><td>
                        <span class="badge bg-{{ $property->listing_type == 'sale' ? 'primary' : 'info' }}">
                            {{ $property->listing_type == 'sale' ? 'For Sale' : 'For Rent' }}
                        </span>
                    </td></tr>
                    <tr><td class="text-muted">Price</td><td><strong>{{ $property->formatted_price }}</strong></td></tr>
                    <tr><td class="text-muted">Area</td><td>{{ $property->area ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Bedrooms</td><td>{{ $property->bedrooms ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Bathrooms</td><td>{{ $property->bathrooms ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Floor</td><td>{{ $property->floor ?? '—' }}</td></tr>
                    <tr><td class="text-muted">City</td><td>{{ $property->city }}</td></tr>
                    <tr><td class="text-muted">Locality</td><td>{{ $property->locality ?? '—' }}</td></tr>
                    <tr><td class="text-muted">RERA No.</td><td>{{ $property->rera_number ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Owner</td><td>
                        @if($property->owner)
                            <a href="{{ route('crm.clients.show', $property->owner) }}">{{ $property->owner->name }}</a>
                        @else — @endif
                    </td></tr>
                    <tr><td class="text-muted">Added By</td><td>{{ $property->addedBy->name ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Status</td><td>
                        @php $sc = ['available'=>'success','reserved'=>'warning','sold'=>'danger','rented'=>'info']; @endphp
                        <span class="badge bg-{{ $sc[$property->status] ?? 'secondary' }}">
                            {{ config('crm.property_status')[$property->status] ?? $property->status }}
                        </span>
                    </td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @if($property->description)
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Description</h6></div>
            <div class="card-body"><p class="mb-0">{{ $property->description }}</p></div>
        </div>
        @endif

        {{-- Site Visits --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Site Visits</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Lead</th><th>Agent</th><th>Date</th><th>Status</th><th>Interest</th></tr></thead>
                    <tbody>
                        @forelse($property->siteVisits as $visit)
                        <tr>
                            <td>{{ $visit->lead->name ?? '—' }}</td>
                            <td>{{ $visit->agent->name ?? '—' }}</td>
                            <td>{{ $visit->visit_datetime->format('d M, h:i A') }}</td>
                            <td><span class="badge bg-{{ $visit->status == 'completed' ? 'success' : 'warning' }}">{{ ucfirst($visit->status) }}</span></td>
                            <td>{{ $visit->interest_level ? ucfirst($visit->interest_level) : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-2">No visits yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Deals --}}
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="fas fa-handshake me-2"></i>Deals</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Deal</th><th>Client</th><th>Value</th><th>Stage</th></tr></thead>
                    <tbody>
                        @forelse($property->deals as $deal)
                        <tr>
                            <td><a href="{{ route('crm.deals.show', $deal) }}">{{ $deal->title }}</a></td>
                            <td>{{ $deal->client->name }}</td>
                            <td>{{ $deal->currency }} {{ number_format($deal->deal_value) }}</td>
                            <td>
                                @php $stc = ['new'=>'secondary','site_visit'=>'info','negotiation'=>'warning','docs_pending'=>'primary','won'=>'success','lost'=>'danger']; @endphp
                                <span class="badge bg-{{ $stc[$deal->stage] ?? 'secondary' }}">
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
    </div>
</div>
@endsection