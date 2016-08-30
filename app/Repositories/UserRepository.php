<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\BaseModel;
use Wizdraw\Models\User;
use Wizdraw\Services\Entities\FacebookUser;

/**
 * Class UserRepository
 * @package Wizdraw\Repositories
 */
class UserRepository extends BaseRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return User::class;
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
     * @return BaseModel
     */
    public function updateFacebook(FacebookUser $facebookUser) : BaseModel
    {
        $user = $this->fromFacebookUser($facebookUser);

        return $this->updateModel($user, $facebookUser->getId(), 'facebook_id');
    }

}