<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Client;

/**
 * Class ClientRepository
 * @package Wizdraw\Repositories
 */
class ClientRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

}