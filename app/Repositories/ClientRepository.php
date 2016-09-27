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

    /**
     * @param string $phone
     *
     * @return mixed
     */
    public function findByPhone(string $phone)
    {
        return $this->findByField('phone', $phone);
    }

}