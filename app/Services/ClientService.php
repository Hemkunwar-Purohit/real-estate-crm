<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ClientService
{
    public function create(array $data): Client
    {
        $data['assigned_to'] = $data['assigned_to'] ?? Auth::id();

        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);

        return $client->fresh();
    }
}
