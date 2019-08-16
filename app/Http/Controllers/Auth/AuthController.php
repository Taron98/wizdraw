<?php

namespace Wizdraw\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
use Wizdraw\Notifications\ClientVerify;
use Wizdraw\Notifications\ClientWelcome;
use Wizdraw\Repositories\ClientRepository;
use Wizdraw\Repositories\UserRepository;
use Wizdraw\Services\AuthService;
use Wizdraw\Services\ClientService;
use Wizdraw\Services\FacebookService;
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

    /**
     * AuthController constructor.
     *
     * @param FacebookService $facebookService
     * @param AuthService $authService
     * @param UserRepository $userRepository
     * @param UserService $userService
     * @param ClientService $clientService
     * @param ClientRepository $clientRepository
     */
    public function __construct(
        FacebookService $facebookService,
        AuthService $authService,
        UserRepository $userRepository,
        UserService $userService,
        ClientService $clientService,
        ClientRepository $clientRepository
    )
    {
        $this->facebookService = $facebookService;
        $this->authService = $authService;
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
        $this->userService = $userService;
        $this->clientService = $clientService;
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
    ): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $token = $this->authenticate($credentials);

        if ($token instanceof JsonResponse) {
            return $token;
        }

        $user = $request->user();
        $hasGroup = $user->client->adminGroups->count() > 0;

        // todo: relocation?
        $user->setLastLoginAt(Carbon::now());
        $this->userService->updateModel($user);

        return $this->respond(array_merge([
            'token' => $token,
            'didSetup' => $user->client->isDidSetup(),
            'hasGroup' => $hasGroup,
            'canTransfer' => $user->client->canTransfer(),
            'noPassword' => $user->hasNoPassword(),
        ], $user->toArray()));
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
    ): JsonResponse
    {
        $userAttrs = $request->only('email', 'deviceId');
        $clientAttrs = $request->only('firstName', 'lastName', 'phone');
        $phone = $request->only('phone')['phone'];
        $user = $this->userRepository->findByField('email', $request->only('email'))->first();
        $client = $this->clientService->findByPhone($phone);
        if (($client != null && $client->user != null && $client->clientType == 'sender' || $user != null)) {
            return $this->respondWithError('user_already_exists', Response::HTTP_BAD_REQUEST);
        }

        if (isset($client) && !is_null($client)) {
            $client = $this->clientService->update($clientAttrs, $client->id);
        } else {
            $client = $this->clientService->createClient($clientAttrs);
        }
        /** @var Client $client */
        if (!$client instanceof Client) {
            return $this->respondWithError('could_not_create_client');
        }

        /** @var User $user */
        $user = $this->userRepository->createWithRelation($userAttrs, $client);
        $client = $this->clientRepository->updateType('sender', $client);
        if (!$user) {
            return $this->respondWithError('could_not_create_user');
        }

        Mail::send('emails.verification.blade.php', ['firstName' => 'Stepan', 'verifyCode' => 123456, 'expire' => 60 * 5 / 60 ], function ($m) {
            $m->from('No-reply@wizdrawapp.com', 'Wizdraw');
            $m->to('mailfortest159357@gmail.com')->subject('Verify your account');
        });
        $client->notify(new ClientVerify(true));
        $client->notify(new ClientWelcome());
        //welcome
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
    public function facebook(
        AuthFacebookRequest $request
    ): JsonResponse
    {
        $requestAttr = $request->inputs();

        try {
            // todo: device_id?
            $facebookUserConnect = $this->facebookService->connect($requestAttr['token'], (int)$requestAttr['expire'],
                $requestAttr['device_id']);

            $facebookUser = $facebookUserConnect['facebookUser'];
        } catch (FacebookInvalidTokenException $exception) {
            return $this->respondWithError($exception->getMessage(), $exception->getStatusCode());
        }

        /** @var User $user */
        $user = $this->userService->findByFacebookId($facebookUser->getId());
        /** @var Client $client */
        $client = $user->client;
        $token = $this->authenticate([], $facebookUser->getId());

        if ($token instanceof JsonResponse) {
            return $token;
        }

        // Returns our token, including his facebook information
        return $this->respond(array_merge([
            'token' => $token,
            'didSetup' => $client->isDidSetup(),
            'isPending' => $user->isPending(),
            'phone' => $client->getPhone(),
            'facebookUserAlreadyExist' => $facebookUserConnect['exist'],
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
    private function authenticate(
        array $credentials = [],
        string $facebookId = ''
    )
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
