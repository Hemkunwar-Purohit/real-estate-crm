<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with(['owner', 'addedBy'])->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('city', 'like', '%'.$request->search.'%')
                    ->orWhere('locality', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('listing_type')) {
            $query->where('listing_type', $request->listing_type);
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        $properties = $query->paginate(config('crm.per_page'))->withQueryString();

        return view('crm.properties.index', compact('properties'));
    }

    public function create()
    {
        $owners = Client::whereIn('type', ['seller', 'landlord'])->orderBy('name')->get();

        return view('crm.properties.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'property_type' => 'required|string',
            'listing_type' => 'required|in:sale,rent',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:100',
            'status' => 'required|string',
        ]);

        Property::create(array_merge($request->all(), [
            'added_by' => Auth::id(),
        ]));

        return redirect()
            ->route('crm.properties.index')
            ->with('success', 'Property added successfully!');
    }

    public function show(Property $property)
    {
        $property->load(['owner', 'addedBy', 'deals.client', 'siteVisits.lead']);

        return view('crm.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $owners = Client::whereIn('type', ['seller', 'landlord'])->orderBy('name')->get();

        return view('crm.properties.edit', compact('property', 'owners'));
    }

    public function update(Request $request, Property $property)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'property_type' => 'required|string',
            'listing_type' => 'required|in:sale,rent',
            'price' => 'required|numeric|min:0',
            'city' => 'required|string|max:100',
            'status' => 'required|string',
        ]);

        $property->update($request->all());

        return redirect()
            ->route('crm.properties.show', $property)
            ->with('success', 'Property updated successfully!');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()
            ->route('crm.properties.index')
            ->with('success', 'Property deleted!');
    }
}
