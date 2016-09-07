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
     * Convert facebook user entity into client model
     *
     * @param FacebookUser $facebookUser
     *
     * @return Client
     */
    public function fromFacebookUser(FacebookUser $facebookUser)
    {
        $client = new Client();
        $client->firstName = $facebookUser->getFirstName();
        $client->middleName = $facebookUser->getMiddleName();
        $client->lastName = $facebookUser->getLastName();

        return $client;
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
        $client = $this->fromFacebookUser($facebookUser);

        return $this->createModel($client);
    }

}