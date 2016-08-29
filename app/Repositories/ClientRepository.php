<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Client;

/**
 * Class ClientRepository
 * @package Wizdraw\Repositories
 */
class ClientRepository extends BaseRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

}