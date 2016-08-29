<?php

namespace Wizdraw\Http\Controllers\Auth;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Wizdraw\Exceptions\FacebookInvalidTokenException;
use Wizdraw\Exceptions\FacebookResponseException;
use Wizdraw\Http\Controllers\BaseController;
use Wizdraw\Http\Controllers\Controller;
use Wizdraw\Http\Requests\LoginFacebookRequest;
use Wizdraw\Http\Requests\LoginRequest;
use Wizdraw\Models\User;
use Wizdraw\Services\FacebookService;
use Wizdraw\Services\UserService;

class AuthController extends BaseController
{

    /** @var JWTAuth */
    private $jwtAuth;

    /** @var FacebookService */
    private $facebookService;

    /** @var  UserService */
    private $userService;

    /**
     * AuthController constructor.
     *
     * @param JWTAuth         $jwtAuth
     * @param FacebookService $facebookService
     * @param UserService     $userService
     */
    public function __construct(JWTAuth $jwtAuth, FacebookService $facebookService, UserService $userService)
    {
        $this->jwtAuth = $jwtAuth;
        $this->facebookService = $facebookService;
        $this->userService = $userService;

        // Don't run the auth middleware on the login routes
        $this->middleware('jwt.auth', ['except' => ['', 'loginFacebook']]);
    }

    /**
     * Login route using username and password
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->only('username', 'password');

        return $this->authenticate($credentials);
    }

    /**
     * Login route using facebook connect
     *
     * @param LoginFacebookRequest $request
     *
     * @return JsonResponse
     */
    public function loginFacebook(LoginFacebookRequest $request) : JsonResponse
    {
        try {
            $this->facebookService->setDefaultAccessToken($request->getToken(), $request->getExpire());
            $facebookUser = $this->facebookService->getBasicInfo();
        } catch (FacebookInvalidTokenException $exception) {
            return $this->respondWithError('facebook_invalid_token', Response::HTTP_FORBIDDEN);
        } catch (FacebookResponseException $exception) {
            return $this->respondWithError('facebook_invalid_response', Response::HTTP_BAD_REQUEST);
        }

        // todo: update user in db
//        $this->userService->updateUserFromFacebook($facebookUser);

        return $this->authenticate([], $facebookUser->getId());
    }

    /**
     * @param array  $credentials
     * @param string $facebookId
     *
     * @return JsonResponse
     */
    private function authenticate(array $credentials = [], string $facebookId = '') : JsonResponse
    {
        try {
            if (!empty($credentials)) {
                $token = $this->createTokenFromCredentials($credentials);
            } else {
                $token = $this->createTokenFromUser($facebookId);
            }
        } catch (JWTException $exception) {
            return $this->respondWithError('could_not_create_token', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respond(compact('token'));
    }

    /**
     * @param array $credentials
     *
     * @return string
     */
    private function createTokenFromCredentials(array $credentials) : string
    {
        if (!$token = $this->jwtAuth->attempt($credentials)) {
            return $this->respondWithError('invalid_credentials', Response::HTTP_UNAUTHORIZED);
        }

        return $token;
    }

    /**
     * @param int $facebookId
     *
     * @return string
     */
    private function createTokenFromUser(int $facebookId) : string
    {
        $user = User::whereFacebookId($facebookId)->first();

        return $this->jwtAuth->fromUser($user);
    }

}
