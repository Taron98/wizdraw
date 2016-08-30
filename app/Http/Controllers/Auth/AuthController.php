<?php

namespace Wizdraw\Http\Controllers\Auth;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Wizdraw\Http\Controllers\AbstractController;
use Wizdraw\Http\Requests\LoginFacebookRequest;
use Wizdraw\Http\Requests\LoginRequest;
use Wizdraw\Repositories\UserRepository;
use Wizdraw\Services\AuthService;
use Wizdraw\Services\FacebookService;

/**
 * Class AuthController
 * @package Wizdraw\Http\Controllers\Auth
 */
class AuthController extends AbstractController
{

    /** @var FacebookService */
    private $facebookService;

    /** @var  UserRepository */
    private $userRepository;

    /** @var  AuthService */
    private $authService;

    /**
     * AuthController constructor.
     *
     * @param FacebookService $facebookService
     * @param UserRepository  $userRepository
     * @param AuthService     $authService
     */
    public function __construct(
        FacebookService $facebookService,
        UserRepository $userRepository,
        AuthService $authService
    ) {
        $this->facebookService = $facebookService;
        $this->userRepository = $userRepository;
        $this->authService = $authService;

        // Don't run the auth middleware on the login routes
        $this->middleware('jwt.auth', ['except' => ['login', 'loginFacebook']]);
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
        $this->facebookService->setDefaultAccessToken($request->getToken(), $request->getExpire());
        $facebookUser = $this->facebookService->getBasicInfo();
        $this->userRepository->updateFacebook($facebookUser);

        return $this->authenticate([], $facebookUser->getId());
    }

    /**
     * Create a token for the authenticated user
     *
     * @param array  $credentials
     * @param string $facebookId
     *
     * @return JsonResponse
     */
    private function authenticate(array $credentials = [], string $facebookId = '') : JsonResponse
    {
        try {
            if (!empty($credentials)) {
                $token = $this->authService->createTokenFromCredentials($credentials);
            } else {
                $token = $this->authService->createTokenFromUser($facebookId);
            }
        } catch (JWTException $exception) {
            return $this->respondWithError('could_not_create_token', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (empty($token)) {
            return $this->respondWithError('invalid_credentials', Response::HTTP_UNAUTHORIZED);
        }

        return $this->respond(compact('token'));
    }

}
