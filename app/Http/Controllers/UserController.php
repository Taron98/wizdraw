<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wizdraw\Http\Requests\NoParamRequest;
use Wizdraw\Http\Requests\User\UserPasswordRequest;
use Wizdraw\Http\Requests\User\UserUpdateRequest;
use Wizdraw\Http\Requests\User\UserResetPasswordRequest;
use Wizdraw\Models\User;
use Wizdraw\Notifications\ClientVerify;
use Wizdraw\Notifications\UserResetPassword;
use Wizdraw\Services\ClientService;
use Wizdraw\Services\UserService;

/**
 * Class UserController
 * @package Wizdraw\Http\Controllers
 */
class UserController extends AbstractController
{

    /** @var  UserService */
    private $userService;
    /** @var  ClientService */
    private $clientService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     * @param ClientService $clientService
     */
    public function __construct(UserService $userService, ClientService $clientService)
    {
        $this->userService = $userService;
        $this->clientService = $clientService;
    }

    /**
     * @param UserUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request)
    {
        $userId = $request->user()->getId();
        $user = $this->userService->update($request->inputs(), $userId);

        return $this->respond($user);
    }

    /**
     * Updating password route
     *
     * @param UserPasswordRequest $request
     *
     * @return JsonResponse
     */
    public function password(UserPasswordRequest $request): JsonResponse
    {
        $user = $this->userService->updatePassword($request->user(), $request->input('password'));
        $user['client'] = $user->client;
        $user['didSetup'] = $user->client->isDidSetup();
        return $this->respond($user);
    }

    /**
     * Generating verification code route
     *
     * @param NoParamRequest $request
     *
     * @return JsonResponse
     */
    public function code(NoParamRequest $request): JsonResponse
    {
        $user = $request->user();
        $this->userService->generateVerifyCode($user);

        $user->client->notify(new ClientVerify());

        return $this->respond([
            'verifyCode' => $user->getVerifyCode(),
            'verifyExpire' => (string)$user->getVerifyExpire(),
        ]);
    }

    /**
     * Verify code route
     *
     * @param NoParamRequest $request
     * @param int $verifyCode
     *
     * @return JsonResponse
     */
    public function verify(NoParamRequest $request, $verifyCode): JsonResponse
    {
        $user = $request->user();

        if (is_null($user->getVerifyCode())) {
            $resInputs = ['user' => $user];

            return $this->respondWithError('user_already_verified', Response::HTTP_BAD_REQUEST, $resInputs);
        }

        if ($user->getVerifyCode() != $verifyCode) {
            return $this->respondWithError('invalid_verification_code', Response::HTTP_BAD_REQUEST);
        }

        if ($user->getVerifyExpire()->isPast()) {
            return $this->respondWithError('verification_code_expired', Response::HTTP_BAD_REQUEST);
        }

        //$this->userService->resetVerification($user);

        return $this->respond($user);
    }

    /**
     * User details by device id route
     *
     * @param string $deviceId
     *
     * @return JsonResponse
     */
    public function device(string $deviceId): JsonResponse
    {
        /** @var User $user */
        $user = $this->userService->findByDeviceId($deviceId);

        if (is_null($user)) {
            return $this->respondWithError('device_not_found', Response::HTTP_NOT_FOUND);
        }

        $client = $user->client;

        return $this->respond([
            'user' => [
                'email' => ($user->getEmail()) ?: '',
                'facebookId' => ($user->getFacebookId()) ?: '',
                'noPassword' => $user->hasNoPassword(),
            ],
            'client' => [
                'id' => $client->getId(),
                'firstName' => ($client->getFirstName()) ?: '',
                'middleName' => ($client->getMiddleName()) ?: '',
                'lastName' => ($client->getLastName()) ?: '',
            ],
        ]);
    }

    /**
     * Check on device wizdraw application version
     * User details by device id route
     *
     * @param string $deviceId
     * @param string $versionId - version of the user current installed app
     *
     * @return JsonResponse
     */
    public function version(string $deviceId, string $versionId): JsonResponse
    {
        /** @var User $user */
        $user = $this->userService->findByDeviceId($deviceId);

        if (!versionControl($versionId)) {
            return $this->respondWithError('version_out_of_date', Response::HTTP_VERSION_NOT_SUPPORTED);

        }

        if (is_null($user)) {
            return $this->respondWithError('device_not_found', Response::HTTP_NOT_FOUND);
        }

        $client = $user->client;

        return $this->respond([
            'user' => [
                'email' => ($user->getEmail()) ?: '',
                'facebookId' => ($user->getFacebookId()) ?: '',
                'noPassword' => $user->hasNoPassword(),
            ],
            'client' => [
                'id' => $client->getId(),
                'firstName' => ($client->getFirstName()) ?: '',
                'middleName' => ($client->getMiddleName()) ?: '',
                'lastName' => ($client->getLastName()) ?: '',
            ],
        ]);
    }


    /**
     * @param UserResetPasswordRequest $request
     *
     * @return mixed|JsonResponse
     */
    public function reset(UserResetPasswordRequest $request)
    {
        $email = $request->input('email');
        $phone = $request->input('phone');
        if ($email) {
            $user = $this->userService->findByEmail($email);
        } else {
            $phone = substr($phone, 0, 1) == '+' ? $phone : '+' . $phone;
            $client = $this->clientService->findByPhone($phone);
            $user = $client->user;
        }
        if (is_null($user)) {
            $resInputs = ['email' => $email];

            return $this->respondWithError('email_not_found', Response::HTTP_NOT_FOUND, $resInputs);
        }

        $this->userService->generateVerifyCode($user);
        $user->client->notify(new UserResetPassword($email));
        //$user->notify(new UserResetPassword($email));
        return $user;
    }

}