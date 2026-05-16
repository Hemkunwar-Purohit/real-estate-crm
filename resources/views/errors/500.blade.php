@extends('crm.layouts.app')
@section('page_title', 'Server Error')
@section('main_content')
<div class="text-center py-5">
    <h1 class="display-1 text-muted">500</h1>
    <h3>Something went wrong</h3>
    <p class="text-muted">Please try again later or contact support.</p>
    <a href="{{ route('crm.dashboard') }}" class="btn btn-primary">
        <i class="fas fa-home me-1"></i> Go to Dashboard
    </a>
</div>
@endsection