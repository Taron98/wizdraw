<?php

namespace Wizdraw\Services;

use Illuminate\Support\Collection;
use Wizdraw\Repositories\ClientRepository;

/**
 * Class ClientService
 * @package Wizdraw\Services
 */
class ClientService extends AbstractService
{

    /**
     * ClientService constructor.
     *
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->repository = $clientRepository;
    }

    /**
     * Mass creation of clients
     *
     * @param array $clients
     *
     * @return Collection
     */
    public function createClients(array $clients) : Collection
    {
        $clientModels = new Collection();

        foreach ($clients as $client) {
            $phone = phone_format($client[ 'phone' ]);
            $clientModel = $this->repository->findByPhone($phone)->first();

            if (is_null($clientModel)) {
                $clientModel = $this->repository->create($client);
            }

            $clientModels->push($clientModel);
        }

        return $clientModels;
    }

}