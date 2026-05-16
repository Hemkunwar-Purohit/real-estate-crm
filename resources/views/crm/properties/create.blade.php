@extends('crm.layouts.app')
@section('page_title', 'Add Property')
@section('page_actions')
    <a href="{{ route('crm.properties.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
@endsection

@section('main_content')
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('crm.properties.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-muted fw-bold mb-3">PROPERTY DETAILS</h6>

                    <div class="mb-3">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="e.g. 3BHK Apartment in Bandra West">
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Property Type <span class="text-danger">*</span></label>
                            <select name="property_type" class="form-select @error('property_type') is-invalid @enderror">
                                <option value="">Select Type</option>
                                @foreach(config('crm.property_types') as $key => $label)
                                    <option value="{{ $key }}" {{ old('property_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Listing Type <span class="text-danger">*</span></label>
                            <select name="listing_type" class="form-select">
                                <option value="sale" {{ old('listing_type') == 'sale' ? 'selected' : '' }}>For Sale</option>
                                <option value="rent" {{ old('listing_type') == 'rent' ? 'selected' : '' }}>For Rent</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach(config('crm.property_status') as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', 'available') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select name="currency" class="form-select" style="max-width:90px">
                                    @foreach(config('crm.currencies') as $c)
                                        <option value="{{ $c }}" {{ old('currency', 'INR') == $c ? 'selected' : '' }}>{{ $c }}</option>
                                    @endforeach
                                </select>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price') }}" placeholder="e.g. 5000000">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Area</label>
                            <input type="text" name="area" class="form-control"
                                value="{{ old('area') }}" placeholder="e.g. 1200 sqft">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Floor</label>
                            <input type="text" name="floor" class="form-control"
                                value="{{ old('floor') }}" placeholder="e.g. 3rd">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bedrooms</label>
                            <input type="number" name="bedrooms" class="form-control"
                                value="{{ old('bedrooms') }}" placeholder="e.g. 3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bathrooms</label>
                            <input type="number" name="bathrooms" class="form-control"
                                value="{{ old('bathrooms') }}" placeholder="e.g. 2">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Property description...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <h6 class="text-muted fw-bold mb-3">LOCATION & OWNER</h6>

                    <div class="mb-3">
                        <label class="form-label">City <span class="text-danger">*</span></label>
                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city') }}" placeholder="e.g. Mumbai">
                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Locality</label>
                        <input type="text" name="locality" class="form-control"
                            value="{{ old('locality') }}" placeholder="e.g. Bandra West">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control" rows="2"
                            placeholder="Full address...">{{ old('address') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">RERA Number</label>
                        <input type="text" name="rera_number" class="form-control"
                            value="{{ old('rera_number') }}" placeholder="RERA registration no.">
                        <small class="text-muted">Required for new projects in India</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Owner (Seller/Landlord)</label>
                        <select name="owner_id" class="form-select">
                            <option value="">Select Owner</option>
                            @foreach($owners as $owner)
                                <option value="{{ $owner->id }}" {{ old('owner_id') == $owner->id ? 'selected' : '' }}>
                                    {{ $owner->name }} ({{ ucfirst($owner->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Property
                </button>
                <a href="{{ route('crm.properties.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection