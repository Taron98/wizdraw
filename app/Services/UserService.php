<?php

namespace Wizdraw\Services;

use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\User;
use Wizdraw\Repositories\UserRepository;
use Wizdraw\Services\Entities\FacebookUser;

/**
 * Class UserService
 * @package Wizdraw\Services
 */
class UserService extends AbstractService
{

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * @param $deviceId
     *
     * @return mixed
     */
    public function findByDeviceId(string $deviceId)
    {
        return $this->repository->findBy('device_id', $deviceId);
    }

    /**
     * @param $user
     */
    public function generateVerifyCode(User $user)
    {
        $user->generateVerifyCode();
        $this->updateModel($user);
    }

    /**
     * @param User $user
     */
    public function updateIsPending(User $user)
    {
        $user->setIsPending(false);
        $this->updateModel($user);
    }

    /**
     * Update facebook session to the user
     *
     * @param int          $userId
     * @param FacebookUser $facebookUser
     *
     * @return AbstractModel
     */
    public function updateFacebook(int $userId, FacebookUser $facebookUser) : AbstractModel
    {
        $user = $this->repository->fromFacebookUser($facebookUser);

        // TODO: check if $user not null?
        return $this->updateModel($user, $userId);
    }

}