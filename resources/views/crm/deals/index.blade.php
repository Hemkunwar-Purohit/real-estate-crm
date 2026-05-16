@extends('crm.layouts.app')
@section('page_title', 'Deals')

@section('page_actions')
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" id="toggleView">
            <i class="fas fa-columns me-1"></i> Kanban View
        </button>
        <a href="{{ route('crm.deals.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Add Deal
        </a>
    </div>
@endsection

@section('main_content')

{{-- Stats --}}
<div class="row mb-3">
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-info"><i class="fas fa-handshake"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Active Deals</span>
                <span class="info-box-number">{{ $stats['total_count'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-warning"><i class="fas fa-rupee-sign"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Pipeline Value</span>
                <span class="info-box-number">₹{{ number_format($stats['total_value']) }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-success"><i class="fas fa-trophy"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Won This Month</span>
                <span class="info-box-number">{{ $stats['won_count'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="info-box mb-2">
            <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Won Value</span>
                <span class="info-box-number">₹{{ number_format($stats['won_value']) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- KANBAN VIEW --}}
<div id="kanbanView" style="display:none;">
    <div class="d-flex gap-3 overflow-auto pb-3" style="min-height: 500px;">
        @foreach(config('crm.pipeline_stages') as $stageKey => $stageLabel)
            @if(!in_array($stageKey, ['won', 'lost']))
            <div class="kanban-col flex-shrink-0" style="width: 260px;">
                <div class="card h-100">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <strong style="font-size:13px;">{{ $stageLabel }}</strong>
                        <span class="badge bg-secondary">
                            {{ isset($kanban[$stageKey]) ? $kanban[$stageKey]->count() : 0 }}
                        </span>
                    </div>
                    <div class="card-body p-2 kanban-cards"
                         data-stage="{{ $stageKey }}"
                         style="min-height:400px; background:#f8f9fa;">

                        @if(isset($kanban[$stageKey]))
                            @foreach($kanban[$stageKey] as $deal)
                            <div class="card mb-2 shadow-sm deal-card"
                                 data-id="{{ $deal->id }}"
                                 draggable="true">
                                <div class="card-body p-2">
                                    <div class="fw-bold" style="font-size:13px;">
                                        <a href="{{ route('crm.deals.show', $deal) }}" class="text-dark">
                                            {{ Str::limit($deal->title, 30) }}
                                        </a>
                                    </div>
                                    <div class="text-muted" style="font-size:12px;">
                                        <i class="fas fa-user me-1"></i>{{ $deal->client->name }}
                                    </div>
                                    @if($deal->property)
                                    <div class="text-muted" style="font-size:12px;">
                                        <i class="fas fa-building me-1"></i>{{ Str::limit($deal->property->title, 25) }}
                                    </div>
                                    @endif
                                    <div class="mt-1 d-flex justify-content-between align-items-center">
                                        <strong class="text-success" style="font-size:12px;">
                                            ₹{{ number_format($deal->deal_value) }}
                                        </strong>
                                        @if($deal->expected_close_date)
                                        <small class="text-muted">
                                            {{ $deal->expected_close_date->format('d M') }}
                                        </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>

{{-- LIST VIEW --}}
<div id="listView">

    {{-- Filters --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Search deal title..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="stage" class="form-select form-select-sm">
                            <option value="">All Stages</option>
                            @foreach(config('crm.pipeline_stages') as $key => $label)
                                <option value="{{ $key }}" {{ request('stage') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
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
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                        <a href="{{ route('crm.deals.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">All Deals <span class="badge bg-primary ms-2">{{ $deals->total() }}</span></h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Deal</th>
                        <th>Client</th>
                        <th>Property</th>
                        <th>Value</th>
                        <th>Commission</th>
                        <th>Stage</th>
                        <th>Close Date</th>
                        <th>Agent</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deals as $deal)
                    @php
                        $stageColors = ['new'=>'secondary','site_visit'=>'info','negotiation'=>'warning','docs_pending'=>'primary','won'=>'success','lost'=>'danger'];
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('crm.deals.show', $deal) }}" class="fw-bold text-dark">
                                {{ $deal->title }}
                            </a>
                        </td>
                        <td>{{ $deal->client->name }}</td>
                        <td>{{ $deal->property ? Str::limit($deal->property->title, 20) : '—' }}</td>
                        <td><strong class="text-success">{{ $deal->currency }} {{ number_format($deal->deal_value) }}</strong></td>
                        <td>{{ $deal->commission ? $deal->currency . ' ' . number_format($deal->commission) : '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $stageColors[$deal->stage] ?? 'secondary' }}">
                                {{ config('crm.pipeline_stages')[$deal->stage] ?? $deal->stage }}
                            </span>
                        </td>
                        <td>{{ $deal->expected_close_date ? $deal->expected_close_date->format('d M Y') : '—' }}</td>
                        <td>{{ $deal->assignedAgent->name }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('crm.deals.show', $deal) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('crm.deals.edit', $deal) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('crm.deals.destroy', $deal) }}"
                                      onsubmit="return confirm('Delete this deal?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="fas fa-handshake fa-2x mb-2 d-block"></i>
                            No deals yet. <a href="{{ route('crm.deals.create') }}">Create first deal</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $deals->links() }}</div>
    </div>
</div>

@endsection

@section('page_js')
<script>
// Toggle between list and kanban view
const toggleBtn  = document.getElementById('toggleView');
const listView   = document.getElementById('listView');
const kanbanView = document.getElementById('kanbanView');
let isKanban = false;

toggleBtn.addEventListener('click', () => {
    isKanban = !isKanban;
    listView.style.display   = isKanban ? 'none'  : 'block';
    kanbanView.style.display = isKanban ? 'block' : 'none';
    toggleBtn.innerHTML = isKanban
        ? '<i class="fas fa-list me-1"></i> List View'
        : '<i class="fas fa-columns me-1"></i> Kanban View';
});

// Drag & Drop Kanban
let draggedCard = null;

document.querySelectorAll('.deal-card').forEach(card => {
    card.addEventListener('dragstart', () => {
        draggedCard = card;
        card.style.opacity = '0.5';
    });
    card.addEventListener('dragend', () => {
        card.style.opacity = '1';
        draggedCard = null;
    });
});

document.querySelectorAll('.kanban-cards').forEach(col => {
    col.addEventListener('dragover', e => e.preventDefault());
    col.addEventListener('drop', async () => {
        if (!draggedCard) return;

        const dealId   = draggedCard.dataset.id;
        const newStage = col.dataset.stage;

        col.appendChild(draggedCard);

        try {
            const res = await fetch(`/crm/deals/${dealId}/stage`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ stage: newStage }),
            });

            const data = await res.json();
            if (!data.success) {
                alert('Failed to update stage!');
            }
        } catch (err) {
            console.error(err);
        }
    });
});
</script>
@endsection