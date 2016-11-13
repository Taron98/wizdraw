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
        return $this->repository->findByField('device_id', $deviceId)->last();
    }

    /**
     * @param int $facebookId
     *
     * @return mixed
     */
    public function findByFacebookId(int $facebookId)
    {
        return $this->repository->findByField('facebook_id', $facebookId)->first();
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
     *
     * @param string $password
     *
     * @return User
     */
    public function updatePassword(User $user, $password)
    {
        $user->setIsPending(false);
        $user->setPassword($password);

//        $this->updateModel($user);
        // todo: that's a temp solution, can't update attributes that are hidden
        // todo: maybe it will be fixed when we'll use repository presenter
        $user->save();

        return $user;
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
        return $this->repository->updateFacebook($id, $facebookUser);
    }

    /**
     * @param User $user
     */
    public function resetVerification(User $user)
    {
        $user->setVerifyCode(null);
        $user->setVerifyExpire(null);

        $this->updateModel($user);
    }

}