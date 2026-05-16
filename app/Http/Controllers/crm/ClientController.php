<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\StoreClientRequest;
use App\Http\Requests\CRM\UpdateClientRequest;
use App\Models\Client;
use App\Models\User;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(
        private ClientService $service
    ) {}

    public function index(Request $request)
    {
        $query = Client::with('assignedAgent')
    ->withCount('deals')  
    ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        $clients = $query->paginate(config('crm.per_page'))->withQueryString();
        $agents = User::orderBy('name')->get();

        return view('crm.clients.index', compact('clients', 'agents'));
    }

    public function create()
    {
        $agents = User::orderBy('name')->get();

        return view('crm.clients.create', compact('agents'));
    }

    public function store(StoreClientRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('crm.clients.index')
            ->with('success', 'Client added successfully!');
    }

    public function show(Client $client)
    {
        $client->load([
            'assignedAgent',
            'deals.property',
            'deals.assignedAgent',
            'properties',
        ]);

        return view('crm.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        $agents = User::orderBy('name')->get();

        return view('crm.clients.edit', compact('client', 'agents'));
    }

    public function update(UpdateClientRequest $request, Client $client)
    {
        $this->service->update($client, $request->validated());

        return redirect()
            ->route('crm.clients.show', $client)
            ->with('success', 'Client updated successfully!');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()
            ->route('crm.clients.index')
            ->with('success', 'Client deleted!');
    }
}
