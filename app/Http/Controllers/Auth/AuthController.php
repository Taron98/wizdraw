<?php

namespace Wizdraw\Http\Controllers\Auth;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Wizdraw\Exceptions\FacebookInvalidTokenException;
use Wizdraw\Http\Controllers\AbstractController;
use Wizdraw\Http\Requests\Auth\AuthFacebookRequest;
use Wizdraw\Http\Requests\Auth\AuthLoginRequest;
use Wizdraw\Http\Requests\Auth\AuthSignupRequest;
use Wizdraw\Models\Client;
use Wizdraw\Models\User;
use Wizdraw\Repositories\ClientRepository;
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

    /** @var  AuthService */
    private $authService;

    /** @var  UserRepository */
    private $userRepository;

    /** @var  ClientRepository */
    private $clientRepository;

    /**
     * AuthController constructor.
     *
     * @param FacebookService  $facebookService
     * @param AuthService      $authService
     * @param UserRepository   $userRepository
     * @param ClientRepository $clientRepository
     */
    public function __construct(
        FacebookService $facebookService,
        AuthService $authService,
        UserRepository $userRepository,
        ClientRepository $clientRepository
    ) {
        $this->facebookService = $facebookService;
        $this->authService = $authService;
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
    }

    /**
     * Login route using username and password
     *
     * @param AuthLoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(AuthLoginRequest $request) : JsonResponse
    {
        $credentials = $request->only('username', 'password');

        $token = $this->authenticate($credentials);

        if ($token instanceof JsonResponse) {
            return $token;
        }

        return $this->respond($token);
    }

    /**
     * Basic signup route
     *
     * @param AuthSignupRequest $request
     *
     * @return JsonResponse
     */
    public function signup(AuthSignupRequest $request) : JsonResponse
    {
        $userAttrs = $request->only('email', 'deviceId');
        $clientAttrs = $request->only('firstName', 'lastName', 'phone');

        if ($this->userRepository->exists($request->only('email'))) {
            return $this->respondWithError('user_already_exists', Response::HTTP_BAD_REQUEST);
        }

        /** @var Client $client */
        $client = $this->clientRepository->create($clientAttrs);
        if (!$client instanceof Client) {
            return $this->respondWithError('cant_create_client');
        }

        /** @var User $user */
        $user = $this->userRepository->createWithRelation($userAttrs, $client);
        if (!$user) {
            return $this->respondWithError('cant_create_user');
        }

        return $this->respond([
            'token'        => $this->authService->createTokenFromUser($user),
            'verifyCode'   => $user->getVerifyCode(),
            'verifyExpire' => (string)$user->getVerifyExpire(),
        ]);
    }

    /**
     * Login route using facebook connect
     * TODO: change the code, seems odd
     *
     * @param AuthFacebookRequest $request
     *
     * @return JsonResponse
     */
    public function facebook(AuthFacebookRequest $request) : JsonResponse
    {
        try {
            $facebookUser = $this->facebookService->connect($request->getToken(), $request->getExpire());
        } catch (FacebookInvalidTokenException $exception) {
            return $this->respondWithError($exception->getMessage(), $exception->getStatusCode());
        }

        $token = $this->authenticate([], $facebookUser->getId());

        if ($token instanceof JsonResponse) {
            return $token;
        }

        // Returns our token, including his facebook information
        return $this->respond(array_merge(compact('token'), $facebookUser->toArray()));
    }

    /**
     * Create a token for the authenticated user
     * TODO: change the code, seems odd
     *
     * @param array  $credentials
     * @param string $facebookId
     *
     * @return JsonResponse|string
     */
    private function authenticate(array $credentials = [], string $facebookId = '')
    {
        try {
            if (!empty($credentials)) {
                $token = $this->authService->createTokenFromCredentials($credentials);
            } else {
                $token = $this->authService->createTokenFromFbId($facebookId);
            }
        } catch (JWTException $exception) {
            return $this->respondWithError('could_not_create_token', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (empty($token)) {
            return $this->respondWithError('invalid_credentials', Response::HTTP_UNAUTHORIZED);
        }

        return $token;
    }

}
