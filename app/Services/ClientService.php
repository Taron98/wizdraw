<?php

namespace Wizdraw\Services;

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

}