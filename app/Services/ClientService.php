<?php

namespace Wizdraw\Services;

use Illuminate\Support\Collection;
use Wizdraw\Models\Client;
use Wizdraw\Repositories\ClientRepository;
use Wizdraw\Services\Entities\FacebookUser;

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
     * Creation of clients
     *
     * @param array $attributes
     *
     * @return Client
     */
    public function createClient(array $attributes) : Client
    {
        return $this->createClients([0 => $attributes])->first();
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
            $clientModel = null;

            if (isset($client[ 'phone' ])) {
                $clientModel = $this->findByPhone($client[ 'phone' ]);
            }

            if (is_null($clientModel)) {
                $clientModel = $this->repository->create($client);
            }

            $clientModels->push($clientModel);
        }

        return $clientModels;
    }

    /**
     * Creating a client by the facebook details
     *
     * @param FacebookUser $facebookUser
     *
     * @return mixed
     */
    public function createByFacebook(FacebookUser $facebookUser)
    {
        $client = $this->repository->makeModel()->fromFacebookUser($facebookUser);

        return $this->createClient($client->toArray());
    }

    /**
     * @param string $phone
     *
     * @return Client|null
     */
    private function findByPhone(string $phone)
    {
        $phone = phone_format($phone);

        return $this->repository->findByPhone($phone)->first();
    }

}