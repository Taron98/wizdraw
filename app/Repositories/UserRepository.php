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
     * @param array         $data
     *
     * @param AbstractModel $client
     *
     * @return mixed
     */
    public function createWithRelation(array $data, AbstractModel $client)
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
     * @param Client       $client
     * @param FacebookUser $facebookUser
     * @param string       $deviceId
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
     * @param FacebookUser $facebookUser
     *
     * @return bool
     */
    public function updateFacebook(FacebookUser $facebookUser) : bool
    {
        $user = $this->model->fromFacebookUser($facebookUser);

        // TODO: check if $user not null?
        return $this->updateModel($user, $facebookUser->getId(), 'facebook_id');
    }

}