<?php

namespace App\Repository;

use App\Exceptions\ClientNotFoundException;
use App\Models\Client;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;



class ClientRepositoryImpl implements ClientRepository
{
    public function getAll(Request $request)
    {
        try {
        $clients = QueryBuilder::for(Client::class)
        ->allowedFilters(['surnom', 'telephone', 'adresse'])
        ->allowedSorts('surnom', 'telephone')
        ->when($request->has('comptes'), function ($query) use ($request) {
            if ($request->input('comptes') === 'oui') {
                $query->whereNotNull('user_id');
            } elseif ($request->input('comptes') === 'non') {
                $query->whereNull('user_id');
            }
        })
        ->when($request->has('active'), function ($query) use ($request) {
            if ($request->input('active') === 'oui') {
                $query->whereHas('user', function ($query) {
                    $query->where('status', true);
                });
            } elseif ($request->input('active') === 'non') {
                $query->whereHas('user', function ($query) {
                    $query->where('status', false);
                });
            }
        })
        ->get();
        return $clients;
        } catch (ClientNotFoundException $e) {
            return $e->getMessage();
        }
    }

    public function listDettes($id)
    {
        $client = Client::with('dettes')->find($id);
        if ($client) {
            $dettes = $client->dettes;
            return $dettes;
        }
    }

    public function findByPhone( $telephone)
    {
        return Client::where('telephone', $telephone)->first();
    }

    public function create( $data)
    {
        return Client::create($data);
    }

    public function find( $id)
    {
        return Client::find($id);
    }

    public function update( $id,  $data)
    {
        $client = $this->find($id);
        if ($client) {
            $client->update($data);
            return $client;
        }

        return null;
    }

    public function delete( $id)
    {
        $client = $this->find($id);
        if ($client) {
            $client->delete();
            return true;
        }

        return false;
    }
}
