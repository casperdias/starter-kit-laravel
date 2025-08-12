<?php

namespace App\Http\Controllers\Passport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passport\ClientRequest;
use App\Http\Resources\Passport\ClientResource;
use Inertia\Inertia;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class ClientController extends Controller
{
    public function index()
    {
        $search = request('search', '');
        $page = request('page', 1);
        $per_page = request('per_page', 5);

        $clients = Client::query()
            ->with('owner')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($per_page, ['*'], 'page', $page);

        return Inertia::render('passport/client/Index', [
            'clients' => ClientResource::collection($clients),
            'search' => $search,
        ]);
    }

    public function store(ClientRequest $request)
    {
        $user = $request->user();

        $client = app(ClientRepository::class)->createAuthorizationCodeGrantClient(
            user: $user,
            name: $request->input('name'),
            redirectUris: $request->input('redirect'),
            confidential: $request->input('confidential', false),
            enableDeviceFlow: $request->input('device_flow', true)
        );

        return back()
            ->with('success', 'Client created successfully.')
            ->with('client_id', $client->id)
            ->with('client_secret', $client->secret);
    }

    public function destroy($client)
    {
        // Logic to delete a passport client
    }
}
