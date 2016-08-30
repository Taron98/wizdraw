<?php

namespace Wizdraw\Services;

use Tymon\JWTAuth\JWTAuth;
use Wizdraw\Models\User;

/**
 * Class AuthService
 * @package Wizdraw\Services
 */
class AuthService extends BaseService
{

    /** @var JWTAuth */
    private $jwtAuth;

    /**
     * AuthService constructor.
     *
     * @param JWTAuth $jwtAuth
     */
    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
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
     * @param int $facebookId
     *
     * @return string
     */
    public function createTokenFromUser(int $facebookId) : string
    {
        $user = User::whereFacebookId($facebookId)->first();

        return $this->jwtAuth->fromUser($user);
    }

}