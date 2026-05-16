<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\StoreLeadRequest;
use App\Http\Requests\CRM\UpdateLeadRequest;
use App\Models\Lead;
use App\Models\User;
use App\Services\LeadService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct(
        private LeadService $service
    ) {}

    /**
     * Leads list with search + filter
     */
    public function index(Request $request)
    {
        $query = Lead::with(['assignedAgent'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Filter by assigned agent
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Filter by listing type
        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }

        $leads = $query->paginate(config('crm.per_page'))->withQueryString();
        $agents = User::orderBy('name')->get();

        return view('crm.leads.index', compact('leads', 'agents'));
    }

    /**
     * Lead create form
     */
    public function create()
    {
        $agents = User::orderBy('name')->get();

        return view('crm.leads.create', compact('agents'));
    }

    /**
     * Lead store
     */
    public function store(StoreLeadRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('crm.leads.index')
            ->with('success', 'Lead added successfully!');
    }

    /**
     * Lead detail
     */
    public function show(Lead $lead)
    {
        $lead->load(['assignedAgent', 'activities.user', 'siteVisits.property', 'convertedClient']);

        return view('crm.leads.show', compact('lead'));
    }

    /**
     * Lead edit form
     */
    public function edit(Lead $lead)
    {
        $agents = User::orderBy('name')->get();

        return view('crm.leads.edit', compact('lead', 'agents'));
    }

    /**
     * Lead update
     */
    public function update(UpdateLeadRequest $request, Lead $lead)
    {
        $this->service->update($lead, $request->validated());

        return redirect()
            ->route('crm.leads.show', $lead)
            ->with('success', 'Lead updated successfully!');
    }

    /**
     * Lead delete
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()
            ->route('crm.leads.index')
            ->with('success', 'Lead deleted successfully!');
    }

    /**
     * Lead ko Client mein convert karo
     */
    public function convert(Lead $lead)
    {
        if ($lead->is_converted) {
            return back()->with('error', 'Lead already converted!');
        }

        $client = $this->service->convertToClient($lead);

        return redirect()
            ->route('crm.clients.show', $client)
            ->with('success', 'Lead converted to Client successfully!');
    }

    /**
     * Lead assign karo
     */
    public function assign(Request $request, Lead $lead)
    {
        $request->validate(['assigned_to' => 'required|exists:users,id']);
        $this->service->assignToAgent($lead, $request->assigned_to);

        return back()->with('success', 'Lead assigned successfully!');
    }
}
