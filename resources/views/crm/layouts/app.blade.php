@extends('adminlte::page')

@section('title', config('crm.app_name'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">@yield('page_title', 'Dashboard')</h1>
        <div>@yield('page_actions')</div>
    </div>
@endsection

@section('content')
    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('main_content')
@endsection

@section('css')
    @yield('page_css')
    <style>
    /* Bootstrap 5 pagination fix for AdminLTE */
    .pagination { margin-bottom: 0; }
    .page-item.active .page-link { background-color: #007bff; border-color: #007bff; }
</style>
@endsection

@section('js')
    @yield('page_js')
    <script>
    // Auto-hide flash messages after 4 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            const bsAlert = new bootstrap.Alert(el);
            bsAlert.close();
        });
    }, 4000);
</script>
@endsection