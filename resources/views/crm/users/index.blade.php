@extends('crm.layouts.app')
@section('page_title', 'User Management')

@section('page_actions')
    <a href="{{ route('crm.users.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add User
    </a>
@endsection

@section('main_content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            All Users
            <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
        </h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover table-sm mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Leads</th>
                    <th>Deals</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $roleColors = [
                        'admin'   => 'danger',
                        'manager' => 'warning',
                        'agent'   => 'info',
                    ];
                    $role = $user->roles->first();
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $user->name }}</strong>
                        @if($user->id === auth()->id())
                            <span class="badge bg-secondary ms-1">You</span>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($role)
                            <span class="badge bg-{{ $roleColors[$role->name] ?? 'secondary' }}">
                                {{ ucfirst($role->name) }}
                            </span>
                        @else
                            <span class="text-muted">No Role</span>
                        @endif
                    </td>
                    <td><span class="badge bg-secondary">{{ $user->leads_count ?? 0 }}</span></td>
                    <td><span class="badge bg-secondary">{{ $user->deals_count ?? 0 }}</span></td>
                    <td><small>{{ $user->created_at->format('d M Y') }}</small></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('crm.users.edit', $user) }}"
                               class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('crm.users.destroy', $user) }}"
                                  onsubmit="return confirm('Delete this user?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $users->links() }}</div>
</div>

@endsection