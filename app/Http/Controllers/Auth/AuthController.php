<?php

namespace Wizdraw\Http\Controllers\Auth;

use JWTAuth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Wizdraw\Exceptions\FacebookInvalidTokenException;
use Wizdraw\Http\Controllers\AbstractController;
use Wizdraw\Http\Requests\Auth\AuthFacebookRequest;
use Wizdraw\Http\Requests\Auth\AuthLoginRequest;
use Wizdraw\Http\Requests\Auth\AuthSignupRequest;
use Wizdraw\Models\Client;
use Wizdraw\Models\User;
use Wizdraw\Repositories\UserRepository;
use Wizdraw\Services\AuthService;
use Wizdraw\Services\ClientService;
use Wizdraw\Services\FacebookService;
use Wizdraw\Services\SmsService;
use Wizdraw\Services\UserService;


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

    /** @var UserService */
    private $userService;

    /** @var  ClientService */
    private $clientService;

    /** @var  SmsService */
    private $smsService;

    /**
     * AuthController constructor.
     *
     * @param FacebookService $facebookService
     * @param AuthService $authService
     * @param UserRepository $userRepository
     * @param UserService $userService
     * @param ClientService $clientService
     * @param SmsService $smsService
     */
    public function __construct(
        FacebookService $facebookService,
        AuthService $authService,
        UserRepository $userRepository,
        UserService $userService,
        ClientService $clientService,
        SmsService $smsService

    ) {
        $this->facebookService = $facebookService;
        $this->authService = $authService;
        $this->userRepository = $userRepository;
        $this->userService = $userService;
        $this->clientService = $clientService;
        $this->smsService = $smsService;

    }

    /**
     * Login route using email and password
     *
     * @param AuthLoginRequest $request
     *
     * @return JsonResponse
     */
    public
    function login(
        AuthLoginRequest $request
    ) : JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $token = $this->authenticate($credentials);

        if ($token instanceof JsonResponse) {
            return $token;
        }

        return $this->respond([
            'token'    => $token,
            'didSetup' => $request->user()->client->isDidSetup(),
        ]);
    }

    /**
     * Basic signup route
     *
     * @param AuthSignupRequest $request
     *
     * @return JsonResponse
     */
    public
    function signup(
        AuthSignupRequest $request
    ) : JsonResponse
    {
        $userAttrs = $request->only('email', 'deviceId');
        $clientAttrs = $request->only('firstName', 'lastName', 'phone');

        if ($this->userRepository->exists($request->only('email'))) {
            return $this->respondWithError('user_already_exists', Response::HTTP_BAD_REQUEST);
        }

        /** @var Client $client */
        $client = $this->clientService->createClient($clientAttrs);
        if (!$client instanceof Client) {
            return $this->respondWithError('cant_create_client');
        }

        /** @var User $user */
        $user = $this->userRepository->createWithRelation($userAttrs, $client);
        if (!$user) {
            return $this->respondWithError('cant_create_user');
        }

        // todo: relocation?
        $sms = $this->smsService->sendSms($clientAttrs[ 'phone' ], $user[ 'verify_code' ]);
        if (!$sms) {
            return $this->respondWithError('problem sending SMS');
        }

        return $this->respond([
            'token' => $this->authService->createTokenFromUser($user),
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
    public
    function facebook(
        AuthFacebookRequest $request
    ) : JsonResponse
    {
        $requestAttr = $request->inputs();

        try {
            // todo: device_id?
            $facebookUser = $this->facebookService->connect($requestAttr[ 'token' ], $requestAttr[ 'expire' ],
                $requestAttr[ 'device_id' ]);
        } catch (FacebookInvalidTokenException $exception) {
            return $this->respondWithError($exception->getMessage(), $exception->getStatusCode());
        }

        /** @var Client $client */
        $client = $this->userService->findByFacebookId($facebookUser->getId())->client;
        $token = $this->authenticate([], $facebookUser->getId());

        if ($token instanceof JsonResponse) {
            return $token;
        }

        // Returns our token, including his facebook information
        return $this->respond(array_merge([
            'token'    => $token,
            'didSetup' => $client->isDidSetup(),
        ], $facebookUser->toArray()));
    }

    /**
     * Refreshing token route
     *
     * @return JsonResponse
     */
    public function token()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return $this->respondWithError('token_not_provided', Response::HTTP_BAD_REQUEST);
        }

        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $exception) {
            return $this->respondWithError('token_invalid');
        }

        return $this->respond(compact('token'));
    }

    /**
     * Create a token for the authenticated user
     * TODO: change the code, seems odd
     *
     * @param array $credentials
     * @param string $facebookId
     *
     * @return JsonResponse|string
     */
    private
    function authenticate(
        array $credentials = [],
        string $facebookId = ''
    ) {
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
