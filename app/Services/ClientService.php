<?php

namespace Wizdraw\Services;

use Wizdraw\Models\Client;
use Wizdraw\Repositories\ClientRepository;

/**
 * Class ClientService
 * @package Wizdraw\Services
 */
class ClientService extends AbstractService
{

    /** @var ClientRepository */
    private $clientRepository;

    /**
     * ClientService constructor.
     *
     * @param ClientRepository $clientRepository
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param array  $data
     * @param mixed  $id
     * @param string $attribute
     *
     * @return Client
     */
    public function update(array $data, $id, $attribute = "id") : Client
    {
        $this->clientRepository->update($data, $id, $attribute);
        $client = $this->clientRepository->find($id);

        return $client;
    }

}