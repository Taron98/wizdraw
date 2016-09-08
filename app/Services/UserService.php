<?php

namespace Wizdraw\Services;

use Wizdraw\Models\User;
use Wizdraw\Repositories\UserRepository;

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

}