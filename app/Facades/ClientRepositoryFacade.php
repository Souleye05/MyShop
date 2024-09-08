<?php
namespace App\Facades;

use App\Repository\ClientRepository;
use Illuminate\Support\Facades\Facade;

class ClientRepositoryFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ClientRepository::class;
    }
}
