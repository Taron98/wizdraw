<?php

namespace Wizdraw\Services;

use Tymon\JWTAuth\JWTAuth;
use Wizdraw\Models\User;
use Wizdraw\Repositories\UserRepository;

/**
 * Class AuthService
 * @package Wizdraw\Services
 */
class AuthService extends AbstractService
{

    /** @var JWTAuth */
    private $jwtAuth;

    /** @var UserRepository */
    private $userRepository;

    /**
     * AuthService constructor.
     *
     * @param JWTAuth        $jwtAuth
     * @param UserRepository $userRepository
     */
    public function __construct(JWTAuth $jwtAuth, UserRepository $userRepository)
    {
        $this->jwtAuth = $jwtAuth;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $credentials
     *
     * @return string
     */
    public function createTokenFromCredentials(array $credentials) : string
    {
        $token = $this->jwtAuth->attempt($credentials);

        return $token;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function createTokenFromUser(User $user) : string
    {
        return $this->jwtAuth->fromUser($user);
    }

    /**
     * @param int $facebookId
     *
     * @return string
     */
    public function createTokenFromFbId(int $facebookId) : string
    {
        $user = $this->userRepository->findByFacebookId($facebookId);

        return $this->jwtAuth->fromUser($user);
    }

}