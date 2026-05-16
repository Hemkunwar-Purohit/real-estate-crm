<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\StoreDealRequest;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Property;
use App\Models\User;
use App\Services\DealService;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function __construct(
        private DealService $service
    ) {}

    public function index(Request $request)
    {
        $query = Deal::with(['client', 'property', 'assignedAgent'])->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $deals = $query->paginate(config('crm.per_page'))->withQueryString();
        $agents = User::orderBy('name')->get();

        // Kanban data — all deals grouped by stage
        $kanban = Deal::with(['client', 'property'])
            ->whereNotIn('stage', ['won', 'lost'])
            ->get()
            ->groupBy('stage');

        // Stats
        $stats = [
            'total_value' => Deal::active()->sum('deal_value'),
            'won_value' => Deal::won()->whereMonth('actual_close_date', now()->month)->sum('deal_value'),
            'total_count' => Deal::active()->count(),
            'won_count' => Deal::won()->whereMonth('actual_close_date', now()->month)->count(),
        ];

        return view('crm.deals.index', compact('deals', 'agents', 'kanban', 'stats'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $properties = Property::available()->orderBy('title')->get();
        $leads = Lead::active()->orderBy('name')->get();
        $agents = User::orderBy('name')->get();

        // Pre-fill from query params (from client/lead page)
        $selectedClient = request('client_id');
        $selectedLead = request('lead_id');

        return view('crm.deals.create', compact(
            'clients', 'properties', 'leads', 'agents',
            'selectedClient', 'selectedLead'
        ));
    }

    public function store(StoreDealRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('crm.deals.index')
            ->with('success', 'Deal created successfully!');
    }

    public function show(Deal $deal)
    {
        $deal->load(['client', 'property', 'lead', 'assignedAgent', 'activities.user']);

        return view('crm.deals.show', compact('deal'));
    }

    public function edit(Deal $deal)
    {
        $clients = Client::orderBy('name')->get();
        $properties = Property::orderBy('title')->get();
        $leads = Lead::orderBy('name')->get();
        $agents = User::orderBy('name')->get();

        return view('crm.deals.edit', compact('deal', 'clients', 'properties', 'leads', 'agents'));
    }

    public function update(StoreDealRequest $request, Deal $deal)
    {
        $this->service->update($deal, $request->validated());

        return redirect()
            ->route('crm.deals.show', $deal)
            ->with('success', 'Deal updated successfully!');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();

        return redirect()
            ->route('crm.deals.index')
            ->with('success', 'Deal deleted!');
    }

    /**
     * Stage update — AJAX call from Kanban
     */
    public function updateStage(Request $request, Deal $deal)
    {
        $request->validate(['stage' => 'required|string']);

        $this->service->updateStage($deal, $request->stage);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stage updated!',
                'stage' => $deal->fresh()->stage,
            ]);
        }

        return back()->with('success', 'Stage updated!');
    }
}
