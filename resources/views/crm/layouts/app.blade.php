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

    /* Mobile fixes */
@media (max-width: 768px) {
    .small-box { margin-bottom: 10px; }
    .info-box   { margin-bottom: 10px; }
    .card-body  { padding: 0.75rem; }
    .btn-group-sm .btn { padding: 0.2rem 0.4rem; }
    table { font-size: 12px; }
}

/* Table responsive */
.table-responsive-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

/* Flash message */
.alert { border-radius: 8px; }

/* Kanban scrollbar */
.kanban-cards::-webkit-scrollbar { width: 4px; }
.kanban-cards::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 2px; }

/* Card hover effect */
.deal-card { cursor: grab; transition: transform .15s, box-shadow .15s; }
.deal-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1) !important; }

/* Badge pills */
.badge { font-weight: 500; }

/* Page header spacing */
.content-header { padding: 10px 15px; }
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