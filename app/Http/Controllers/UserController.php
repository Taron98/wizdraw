<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wizdraw\Http\Requests\NoParamRequest;
use Wizdraw\Http\Requests\User\UserPasswordRequest;
use Wizdraw\Models\User;
use Wizdraw\Services\UserService;

/**
 * Class UserController
 * @package Wizdraw\Http\Controllers
 */
class UserController extends AbstractController
{

    /** @var  UserService */
    private $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Updating password route
     *
     * @param UserPasswordRequest $request
     *
     * @return JsonResponse
     */
    public function password(UserPasswordRequest $request) : JsonResponse
    {
        $user = $this->userService->update($request->inputs(), $request->user()->getId());

        return $this->respond($user);
    }

    /**
     * Generating verification code route
     *
     * @param NoParamRequest $request
     *
     * @return JsonResponse
     */
    public function code(NoParamRequest $request) : JsonResponse
    {
        $user = $request->user();

        if (is_null($user->getVerifyExpire()) || $user->getVerifyExpire()->isPast()) {
            $this->userService->generateVerifyCode($user);
        }

        return $this->respond([
            'verifyCode'   => $user->getVerifyCode(),
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
    public function verify(NoParamRequest $request, int $verifyCode) : JsonResponse
    {
        $user = $request->user();

//        if (!$user->isPending()) {
//            return $this->respondWithError('user_already_verified', Response::HTTP_BAD_REQUEST);
//        }

        if ($user->getVerifyCode() !== $verifyCode) {
            return $this->respondWithError('invalid_verification_code', Response::HTTP_BAD_REQUEST);
        }

        if ($user->getVerifyExpire()->isPast()) {
            return $this->respondWithError('verification_code_expired', Response::HTTP_BAD_REQUEST);
        }

        $this->userService->updateIsPending($user);

        return $this->respond($user);
    }

    /**
     * User details by device id route
     *
     * @param string $deviceId
     *
     * @return JsonResponse
     */
    public function device(string $deviceId) : JsonResponse
    {
        /** @var User $user */
        $user = $this->userService->findByDeviceId($deviceId);

        return $this->respond([
            'email'      => ($user->getEmail()) ?: '',
            'facebookId' => ($user->getFacebookId()) ?: '',
            'fullName'   => $user->client->getFullName(),
        ]);
    }

}