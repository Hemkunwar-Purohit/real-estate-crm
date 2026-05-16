@extends('crm.layouts.app')
@section('page_title', 'Page Not Found')
@section('main_content')
<div class="text-center py-5">
    <h1 class="display-1 text-muted">404</h1>
    <h3>Page Not Found</h3>
    <p class="text-muted">The page you are looking for does not exist.</p>
    <a href="{{ route('crm.dashboard') }}" class="btn btn-primary">
        <i class="fas fa-home me-1"></i> Go to Dashboard
    </a>
</div>
@endsection