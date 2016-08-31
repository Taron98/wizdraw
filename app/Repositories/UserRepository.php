<?php

namespace Wizdraw\Repositories;

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
     * @param int $facebookId
     *
     * @return mixed
     */
    public function findByFacebookId(int $facebookId)
    {
        return $this->findBy('facebook_id', $facebookId);
    }

    /**
     * Convert facebook user entity into user model
     *
     * @param FacebookUser $facebookUser
     *
     * @return User
     */
    public function fromFacebookUser(FacebookUser $facebookUser)
    {
        $user = new User();
        $user->facebookId = $facebookUser->getId();
        $user->facebookToken = $facebookUser->getToken();
        $user->facebookTokenExpire = $facebookUser->getExpire();

        return $user;
    }

    /**
     * Update facebook token, expire and id to our user
     *
     * @param FacebookUser $facebookUser
     *
     * @return bool
     */
    public function updateFacebook(FacebookUser $facebookUser) : bool
    {
        $user = $this->fromFacebookUser($facebookUser);

        // TODO: check if $user not null?
        return $this->updateModel($user, $facebookUser->getId(), 'facebook_id');
    }

}