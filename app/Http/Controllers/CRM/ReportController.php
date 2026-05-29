<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\Property;
use App\Models\SiteVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    /**
     * Reports main page
     */
    public function index()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Revenue this month
        $revenueThisMonth = Deal::won()
            ->whereMonth('actual_close_date', $currentMonth)
            ->whereYear('actual_close_date', $currentYear)
            ->sum('deal_value');

        // Revenue last month
        $revenueLastMonth = Deal::won()
            ->whereMonth('actual_close_date', now()->subMonth()->month)
            ->whereYear('actual_close_date', now()->subMonth()->year)
            ->sum('deal_value');

        // Revenue growth %
        $revenueGrowth = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100, 1)
            : 0;

        // Monthly revenue — last 12 months
        $monthlyRevenue = Deal::won()
            ->where('actual_close_date', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('YEAR(actual_close_date) as year, MONTH(actual_close_date) as month, SUM(deal_value) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Deals by stage
        $dealsByStage = Deal::selectRaw('stage, COUNT(*) as count, SUM(deal_value) as total_value')
            ->groupBy('stage')
            ->get();

        // Leads by source
        $leadsBySource = Lead::selectRaw('source, COUNT(*) as count')
            ->whereNotNull('source')
            ->groupBy('source')
            ->orderByDesc('count')
            ->get();

        // Top agents by deals won
        $topAgents = Deal::won()
            ->with('assignedAgent:id,name')
            ->selectRaw('assigned_to, COUNT(*) as deals_count, SUM(deal_value) as total_value')
            ->groupBy('assigned_to')
            ->orderByDesc('total_value')
            ->take(5)
            ->get();

        // Site visit conversion rate
        $totalVisits = SiteVisit::count();
        $completedVisits = SiteVisit::where('status', 'completed')->count();
        $conversionRate = $totalVisits > 0 ? round(($completedVisits / $totalVisits) * 100) : 0;

        // Summary stats
        $stats = [
            'total_leads' => Lead::count(),
            'converted_leads' => Lead::where('is_converted', true)->count(),
            'total_clients' => Client::count(),
            'total_properties' => Property::count(),
            'total_deals' => Deal::count(),
            'won_deals' => Deal::won()->count(),
            'total_revenue' => Deal::won()->sum('deal_value'),
            'total_commission' => Deal::won()->sum('commission'),
        ];

        return view('crm.reports.index', compact(
            'stats',
            'revenueThisMonth',
            'revenueLastMonth',
            'revenueGrowth',
            'monthlyRevenue',
            'dealsByStage',
            'leadsBySource',
            'topAgents',
            'totalVisits',
            'conversionRate'
        ));
    }

    /**
     * Leads report
     */
    public function leads(Request $request)
    {
        $query = Lead::with('assignedAgent')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $leads = $query->paginate(50)->withQueryString();

        // Summary
        $summary = [
            'total' => $query->count(),
            'converted' => Lead::where('is_converted', true)->count(),
            'new' => Lead::where('status', 'new')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
        ];

        return view('crm.reports.leads', compact('leads', 'summary'));
    }

    /**
     * Deals report
     */
    public function deals(Request $request)
    {
        $query = Deal::with(['client', 'property', 'assignedAgent'])->latest();

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $deals = $query->paginate(50)->withQueryString();

        $summary = [
            'total_value' => $query->sum('deal_value'),
            'total_commission' => $query->sum('commission'),
            'won_count' => Deal::won()->count(),
            'won_value' => Deal::won()->sum('deal_value'),
        ];

        return view('crm.reports.deals', compact('deals', 'summary'));
    }

    /**
     * Export Leads to Excel
     */
    public function exportLeads(Request $request)
    {
        $query = Lead::with('assignedAgent')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $leads = $query->get()->map(function ($lead) {
            return [
                'Name' => $lead->name,
                'Phone' => $lead->phone,
                'Email' => $lead->email ?? '—',
                'Source' => config('crm.lead_sources')[$lead->source] ?? '—',
                'Status' => ucfirst($lead->status),
                'Looking To' => ucfirst($lead->listing_type),
                'Budget Min' => $lead->budget_min ? number_format($lead->budget_min) : '—',
                'Budget Max' => $lead->budget_max ? number_format($lead->budget_max) : '—',
                'City' => $lead->preferred_city ?? '—',
                'Agent' => $lead->assignedAgent->name ?? '—',
                'Date Added' => $lead->created_at->format('d M Y'),
            ];
        });

        return (new FastExcel($leads))->download('leads-'.now()->format('Y-m-d').'.xlsx');
    }

    public function exportDeals(Request $request)
    {
        $query = Deal::with(['client', 'property', 'assignedAgent'])->latest();

        if ($request->filled('stage')) {
            $query->where('stage', $request->stage);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $deals = $query->get()->map(function ($deal) {
            return [
                'Title' => $deal->title,
                'Client' => $deal->client->name,
                'Property' => $deal->property ? $deal->property->title : '—',
                'Value (INR)' => number_format($deal->deal_value),
                'Commission' => $deal->commission ? number_format($deal->commission) : '—',
                'Stage' => config('crm.pipeline_stages')[$deal->stage] ?? $deal->stage,
                'Agent' => $deal->assignedAgent->name,
                'Expected Close' => $deal->expected_close_date ? $deal->expected_close_date->format('d M Y') : '—',
                'Actual Close' => $deal->actual_close_date ? $deal->actual_close_date->format('d M Y') : '—',
                'Date Added' => $deal->created_at->format('d M Y'),
            ];
        });

        return (new FastExcel($deals))->download('deals-'.now()->format('Y-m-d').'.xlsx');
    }

    public function properties()
    {
        $properties = Property::with(['owner', 'addedBy'])
            ->withCount(['deals', 'siteVisits'])
            ->latest()
            ->paginate(50);

        $stats = [
            'total' => Property::count(),
            'available' => Property::where('status', 'available')->count(),
            'sold' => Property::where('status', 'sold')->count(),
            'rented' => Property::where('status', 'rented')->count(),
        ];

        return view('crm.reports.properties', compact('properties', 'stats'));
    }
}
