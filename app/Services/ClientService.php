<?php
namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function findOrCreateClient(array $data): Client
    {
        return Client::firstOrCreate(
            ['egn' => $data['egn']],
            [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
            ]
        );
    }
}
