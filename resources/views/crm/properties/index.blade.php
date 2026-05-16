@extends('crm.layouts.app')
@section('page_title', 'Properties')
@section('page_actions')
    <a href="{{ route('crm.properties.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Property
    </a>
@endsection

@section('main_content')

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search title, city, locality..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="listing_type" class="form-select form-select-sm">
                        <option value="">Sale / Rent</option>
                        <option value="sale" {{ request('listing_type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                        <option value="rent" {{ request('listing_type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="property_type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach(config('crm.property_types') as $key => $label)
                            <option value="{{ $key }}" {{ request('property_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        @foreach(config('crm.property_status') as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    <a href="{{ route('crm.properties.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            All Properties
            <span class="badge bg-primary ms-2">{{ $properties->total() }}</span>
        </h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Listing</th>
                    <th>Price</th>
                    <th>City</th>
                    <th>Owner</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($properties as $property)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('crm.properties.show', $property) }}" class="fw-bold text-dark">
                            {{ $property->title }}
                        </a>
                        @if($property->locality)
                            <br><small class="text-muted">{{ $property->locality }}, {{ $property->city }}</small>
                        @endif
                    </td>
                    <td>{{ config('crm.property_types')[$property->property_type] ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $property->listing_type == 'sale' ? 'primary' : 'info' }}">
                            {{ $property->listing_type == 'sale' ? 'For Sale' : 'For Rent' }}
                        </span>
                    </td>
                    <td><strong>{{ $property->formatted_price }}</strong></td>
                    <td>{{ $property->city }}</td>
                    <td>{{ $property->owner->name ?? '—' }}</td>
                    <td>
                        @php
                            $statusColors = ['available'=>'success','reserved'=>'warning','sold'=>'danger','rented'=>'info'];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$property->status] ?? 'secondary' }}">
                            {{ config('crm.property_status')[$property->status] ?? $property->status }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('crm.properties.show', $property) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('crm.properties.edit', $property) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('crm.properties.destroy', $property) }}"
                                  onsubmit="return confirm('Delete this property?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-building fa-2x mb-2 d-block"></i>
                        No properties yet.
                        <a href="{{ route('crm.properties.create') }}">Add first property</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $properties->links() }}</div>
</div>

@endsection