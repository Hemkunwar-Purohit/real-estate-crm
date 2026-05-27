@extends('crm.layouts.app')
@section('page_title', 'Properties Report')

@section('page_actions')
    <a href="{{ route('crm.reports.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')

@php
    $properties = \App\Models\Property::with(['owner', 'addedBy'])
        ->withCount(['deals', 'siteVisits'])
        ->latest()
        ->paginate(50);

    $stats = [
        'total'     => \App\Models\Property::count(),
        'available' => \App\Models\Property::where('status', 'available')->count(),
        'sold'      => \App\Models\Property::where('status', 'sold')->count(),
        'rented'    => \App\Models\Property::where('status', 'rented')->count(),
    ];
@endphp

<div class="row mb-3">
    <div class="col-md-3 col-6">
        <div class="small-box bg-primary"><div class="inner"><h3>{{ $stats['total'] }}</h3><p>Total</p></div><div class="icon"><i class="fas fa-building"></i></div></div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-success"><div class="inner"><h3>{{ $stats['available'] }}</h3><p>Available</p></div><div class="icon"><i class="fas fa-check"></i></div></div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-danger"><div class="inner"><h3>{{ $stats['sold'] }}</h3><p>Sold</p></div><div class="icon"><i class="fas fa-key"></i></div></div>
    </div>
    <div class="col-md-3 col-6">
        <div class="small-box bg-info"><div class="inner"><h3>{{ $stats['rented'] }}</h3><p>Rented</p></div><div class="icon"><i class="fas fa-home"></i></div></div>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">All Properties</h3></div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Title</th><th>Type</th><th>Price</th><th>City</th><th>Status</th><th>Deals</th><th>Visits</th></tr>
            </thead>
            <tbody>
                @foreach($properties as $property)
                @php $sc = ['available'=>'success','reserved'=>'warning','sold'=>'danger','rented'=>'info']; @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="{{ route('crm.properties.show', $property) }}">{{ Str::limit($property->title, 30) }}</a></td>
                    <td>{{ config('crm.property_types')[$property->property_type] ?? '—' }}</td>
                    <td>{{ $property->formatted_price }}</td>
                    <td>{{ $property->city }}</td>
                    <td><span class="badge bg-{{ $sc[$property->status] ?? 'secondary' }}">{{ ucfirst($property->status) }}</span></td>
                    <td><span class="badge bg-secondary">{{ $property->deals_count }}</span></td>
                    <td><span class="badge bg-info">{{ $property->site_visits_count }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $properties->links() }}</div>
</div>

@endsection