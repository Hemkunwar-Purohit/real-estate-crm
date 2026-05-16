@extends('crm.layouts.app')

@section('page_title', 'Clients')

@section('page_actions')
    <a href="{{ route('crm.clients.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Client
    </a>
@endsection

@section('main_content')

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('crm.clients.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Search name, phone, email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="buyer"    {{ request('type') == 'buyer'    ? 'selected' : '' }}>Buyer</option>
                        <option value="seller"   {{ request('type') == 'seller'   ? 'selected' : '' }}>Seller</option>
                        <option value="tenant"   {{ request('type') == 'tenant'   ? 'selected' : '' }}>Tenant</option>
                        <option value="landlord" {{ request('type') == 'landlord' ? 'selected' : '' }}>Landlord</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="assigned_to" class="form-select form-select-sm">
                        <option value="">All Agents</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('assigned_to') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    <a href="{{ route('crm.clients.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            All Clients
            <span class="badge bg-primary ms-2">{{ $clients->total() }}</span>
        </h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>City</th>
                    <th>Source</th>
                    <th>Agent</th>
                    <th>Deals</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td>{{ $loop->iteration + ($clients->currentPage() - 1) * $clients->perPage() }}</td>
                    <td>
                        <a href="{{ route('crm.clients.show', $client) }}" class="fw-bold text-dark">
                            {{ $client->name }}
                        </a>
                        @if($client->email)
                            <br><small class="text-muted">{{ $client->email }}</small>
                        @endif
                    </td>
                    <td><a href="tel:{{ $client->phone }}">{{ $client->phone }}</a></td>
                    <td>
                        @php
                            $typeColors = [
                                'buyer'    => 'primary',
                                'seller'   => 'success',
                                'tenant'   => 'info',
                                'landlord' => 'warning',
                            ];
                        @endphp
                        <span class="badge bg-{{ $typeColors[$client->type] ?? 'secondary' }}">
                            {{ ucfirst($client->type) }}
                        </span>
                    </td>
                    <td>{{ $client->city ?? '—' }}</td>
                    <td>{{ config('crm.lead_sources')[$client->source] ?? $client->source ?? '—' }}</td>
                    <td>{{ $client->assignedAgent->name ?? '—' }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $client->deals_count ?? 0 }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('crm.clients.show', $client) }}"
                               class="btn btn-outline-info" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('crm.clients.edit', $client) }}"
                               class="btn btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('crm.clients.destroy', $client) }}"
                                  onsubmit="return confirm('Delete this client?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2 d-block"></i>
                        No clients yet.
                        <a href="{{ route('crm.clients.create') }}">Add first client</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $clients->links() }}</div>
</div>

@endsection