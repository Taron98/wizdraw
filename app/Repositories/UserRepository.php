<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\Client;
use Wizdraw\Models\User;
use Wizdraw\Services\Entities\FacebookUser;

/**
 * Class UserRepository
 * @package Wizdraw\Repositories
 */
class UserRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return User::class;
    }

    /**
     * @param array $data
     *
     * @param Client $client
     *
     * @return mixed
     */
    public function createWithRelation(array $data, Client $client)
    {
        $newModel = $this->model
            ->newInstance($data)
            ->client()->associate($client);

        $success = $newModel->save();

        return (!$success) ?: $newModel;
    }

    /**
     * Creating a user by the facebook details
     *
     * @param Client $client
     * @param FacebookUser $facebookUser
     * @param string $deviceId
     *
     * @return mixed
     */
    public function createByFacebook(Client $client, FacebookUser $facebookUser, string $deviceId)
    {
        /** @var User $user */
        $user = $this->model->fromFacebookUser($facebookUser);
        $user->setDeviceId($deviceId);

        return $this->createWithRelation($user->toArray(), $client);
    }

    /**
     * Update facebook session to the user
     *
     * @param int $id
     * @param FacebookUser $facebookUser
     *
     * @return AbstractModel
     */
    public function updateFacebook(int $id, FacebookUser $facebookUser) : AbstractModel
    {
        $user = $this->model->fromFacebookUser($facebookUser);
        $user->setId($id);

        // TODO: check if $user not null?
        return $this->updateModel($user);
    }

}