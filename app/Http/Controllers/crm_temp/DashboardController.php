<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Property;
use App\Models\SiteVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats cards
        $stats = [
            'total_leads' => Lead::active()->count(),
            'total_clients' => Client::active()->count(),
            'total_properties' => Property::available()->count(),
            'active_deals' => Deal::active()->count(),
        ];

        // Won this month
        $wonThisMonth = Deal::won()
            ->whereMonth('actual_close_date', now()->month)
            ->whereYear('actual_close_date', now()->year)
            ->sum('deal_value');

        // My leads
        $myLeads = Lead::with('assignedAgent:id,name')
            ->where('assigned_to', $user->id)
            ->active()
            ->latest()
            ->take(5)
            ->get();

        // Upcoming site visits
        $upcomingSiteVisits = SiteVisit::with([
            'lead:id,name,phone',
            'property:id,title,city',
        ])
            ->where('status', 'scheduled')
            ->where('visit_datetime', '>=', now())
            ->orderBy('visit_datetime')
            ->take(5)
            ->get();

        // Today's visits
        $todayVisits = SiteVisit::with(['lead', 'property', 'agent'])
            ->whereDate('visit_datetime', today())
            ->where('status', 'scheduled')
            ->orderBy('visit_datetime')
            ->get();

        // Deals by stage for chart
        $dealsByStage = Deal::active()
            ->selectRaw('stage, COUNT(*) as count, SUM(deal_value) as total_value')
            ->groupBy('stage')
            ->get()
            ->keyBy('stage');

        // Monthly leads — last 6 months
        $monthlyLeads = Lead::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Recent deals
        // $recentDeals = Deal::with(['client', 'property', 'assignedAgent'])
        //     ->latest()
        //     ->take(5)
        //     ->get();

        $recentDeals = Deal::with([
            'client:id,name',
            'property:id,title,city',
            'assignedAgent:id,name',
        ])
            ->latest()
            ->take(5)
            ->get();

        // Lead sources breakdown
        $leadSources = Lead::selectRaw('source, COUNT(*) as count')
            ->whereNotNull('source')
            ->groupBy('source')
            ->orderByDesc('count')
            ->take(5)
            ->get();

        return view('crm.dashboard', compact(
            'stats',
            'myLeads',
            'upcomingSiteVisits',
            'todayVisits',
            'dealsByStage',
            'monthlyLeads',
            'recentDeals',
            'wonThisMonth',
            'leadSources'
        ));
    }
}
