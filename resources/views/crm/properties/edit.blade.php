@extends('crm.layouts.app')
@section('page_title', 'Edit: ' . Str::limit($property->title, 30))
@section('page_actions')
    <a href="{{ route('crm.properties.show', $property) }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.properties.update', $property) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $property->title) }}">
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Property Type</label>
                            <select name="property_type" class="form-select">
                                @foreach(config('crm.property_types') as $key => $label)
                                    <option value="{{ $key }}" {{ old('property_type', $property->property_type) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Listing Type</label>
                            <select name="listing_type" class="form-select">
                                <option value="sale" {{ old('listing_type', $property->listing_type) == 'sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="rent" {{ old('listing_type', $property->listing_type) == 'rent' ? 'selected' : '' }}>For Rent</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(config('crm.property_status') as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $property->status) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price</label>
                            <div class="input-group">
                                <select name="currency" class="form-select" style="max-width:90px">
                                    @foreach(config('crm.currencies') as $c)
                                        <option value="{{ $c }}" {{ old('currency', $property->currency) == $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="price" class="form-control" value="{{ old('price', $property->price) }}">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Area</label>
                            <input type="text" name="area" class="form-control" value="{{ old('area', $property->area) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Floor</label>
                            <input type="text" name="floor" class="form-control" value="{{ old('floor', $property->floor) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bedrooms</label>
                            <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms) }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bathrooms</label>
                            <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $property->bathrooms) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $property->description) }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $property->city) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Locality</label>
                        <input type="text" name="locality" class="form-control" value="{{ old('locality', $property->locality) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $property->address) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">RERA Number</label>
                        <input type="text" name="rera_number" class="form-control" value="{{ old('rera_number', $property->rera_number) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Owner</label>
                        <select name="owner_id" class="form-select">
                            <option value="">No Owner</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ old('owner_id', $property->owner_id) == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update Property
                </button>
                <a href="{{ route('crm.properties.show', $property) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection