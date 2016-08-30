<?php

namespace Wizdraw\Services;

use Wizdraw\Models\User;
use Wizdraw\Services\Entities\FacebookUser;

/**
 * Class UserService
 * @package Wizdraw\Services
 */
class UserService extends AbstractService
{
    public function __construct()
    {
    }

    /**
     * @param FacebookUser $facebookUser
     */
    public function updateUserFromFacebook(FacebookUser $facebookUser)
    {
        User::updateOrCreate($facebookUser->toArray());
    }

}
