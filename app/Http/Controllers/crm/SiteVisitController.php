<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Property;
use App\Models\SiteVisit;
use App\Models\User;
use Illuminate\Http\Request;

class SiteVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = SiteVisit::with(['lead', 'property', 'agent'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        // Today's visits
        if ($request->filled('today')) {
            $query->whereDate('visit_datetime', today());
        }

        $visits = $query->paginate(config('crm.per_page'))->withQueryString();
        $agents = User::orderBy('name')->get();

        // Upcoming visits count
        $upcomingCount = SiteVisit::where('status', 'scheduled')
            ->where('visit_datetime', '>=', now())
            ->count();

        return view('crm.site-visits.index', compact('visits', 'agents', 'upcomingCount'));
    }

    public function create()
    {
        $leads = Lead::active()->orderBy('name')->get();
        $properties = Property::available()->orderBy('title')->get();
        $agents = User::orderBy('name')->get();

        // Pre-fill from query params
        $selectedLead = request('lead_id');
        $selectedProperty = request('property_id');

        return view('crm.site-visits.create', compact(
            'leads', 'properties', 'agents',
            'selectedLead', 'selectedProperty'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'property_id' => 'required|exists:properties,id',
            'agent_id' => 'required|exists:users,id',
            'visit_datetime' => 'required|date|after:now',
        ]);

        SiteVisit::create($request->only([
            'lead_id', 'property_id', 'agent_id', 'visit_datetime',
        ]) + ['status' => 'scheduled']);

        return redirect()
            ->route('crm.site-visits.index')
            ->with('success', 'Site visit scheduled successfully!');
    }

    public function show(SiteVisit $siteVisit)
    {
        $siteVisit->load(['lead', 'property', 'agent']);

        return view('crm.site-visits.show', compact('siteVisit'));
    }

    public function edit(SiteVisit $siteVisit)
    {
        $leads = Lead::orderBy('name')->get();
        $properties = Property::orderBy('title')->get();
        $agents = User::orderBy('name')->get();

        return view('crm.site-visits.edit', compact('siteVisit', 'leads', 'properties', 'agents'));
    }

    public function update(Request $request, SiteVisit $siteVisit)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'property_id' => 'required|exists:properties,id',
            'agent_id' => 'required|exists:users,id',
            'visit_datetime' => 'required|date',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'interest_level' => 'nullable|in:high,medium,low,not_interested',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $siteVisit->update($request->all());

        return redirect()
            ->route('crm.site-visits.show', $siteVisit)
            ->with('success', 'Site visit updated!');
    }

    public function destroy(SiteVisit $siteVisit)
    {
        $siteVisit->delete();

        return redirect()
            ->route('crm.site-visits.index')
            ->with('success', 'Visit deleted!');
    }

    /**
     * Mark visit as completed
     */
    public function markComplete(Request $request, SiteVisit $siteVisit)
    {
        $request->validate([
            'feedback' => 'nullable|string|max:1000',
            'interest_level' => 'nullable|in:high,medium,low,not_interested',
        ]);

        $siteVisit->update([
            'status' => 'completed',
            'feedback' => $request->feedback,
            'interest_level' => $request->interest_level,
        ]);

        return back()->with('success', 'Visit marked as completed!');
    }
}
