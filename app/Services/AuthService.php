<?php

namespace Wizdraw\Services;

use Tymon\JWTAuth\JWTAuth;
use Wizdraw\Models\User;

/**
 * Class AuthService
 * @package Wizdraw\Services
 */
class AuthService extends AbstractService
{

    /** @var JWTAuth */
    private $jwtAuth;

    /** @var UserService */
    private $userService;

    /**
     * AuthService constructor.
     *
     * @param JWTAuth $jwtAuth
     * @param UserService $userService
     */
    public function __construct(JWTAuth $jwtAuth, UserService $userService)
    {
        $this->jwtAuth = $jwtAuth;
        $this->userService = $userService;
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
        $user = $this->userService->findByFacebookId($facebookId);

        return $this->jwtAuth->fromUser($user);
    }

}