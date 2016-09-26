<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Client;
use Wizdraw\Services\Entities\FacebookUser;

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

    /**
     * Creating a client by the facebook details
     *
     * @param FacebookUser $facebookUser
     *
     * @return mixed
     */
    public function createByFacebook(FacebookUser $facebookUser)
    {
        $client = $this->model->fromFacebookUser($facebookUser);

        return $this->createModel($client);
    }

}