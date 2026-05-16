<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadService
{
    /**
     * New lead create karo
     */
    public function create(array $data): Lead
    {
        $data['assigned_to'] = $data['assigned_to'] ?? Auth::id();

        return Lead::create($data);
    }

    /**
     * Lead update karo
     */
    public function update(Lead $lead, array $data): Lead
    {
        $lead->update($data);

        return $lead->fresh();
    }

    /**
     * Lead ko Client mein convert karo
     */
    public function convertToClient(Lead $lead): Client
    {
        return DB::transaction(function () use ($lead) {
            // Client banao lead se
            $client = Client::create([
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'type' => 'buyer',
                'source' => $lead->source,
                'assigned_to' => $lead->assigned_to,
                'status' => 'active',
            ]);

            // Lead ko converted mark karo
            $lead->update([
                'is_converted' => true,
                'converted_client_id' => $client->id,
                'status' => 'converted',
            ]);

            return $client;
        });
    }

    /**
     * Lead assign karo agent ko
     */
    public function assignToAgent(Lead $lead, int $userId): Lead
    {
        $lead->update(['assigned_to' => $userId]);

        return $lead->fresh();
    }
}
