<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wizdraw\Http\Requests\NoParamRequest;
use Wizdraw\Http\Requests\User\UserPasswordRequest;
use Wizdraw\Models\User;
use Wizdraw\Repositories\UserRepository;

/**
 * Class UserController
 * @package Wizdraw\Http\Controllers
 */
class UserController extends AbstractController
{

    /** @var  UserRepository */
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        $user = $request->user();
        $user->setPassword($request->getPassword());

        $this->userRepository->updateModel($user);

        return $this->respond();
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

        if ($user->getVerifyExpire()->isPast()) {
            $user->generateVerifyCode();
            $this->userRepository->updateModel($user);
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
     * @param int            $verifyCode
     *
     * @return JsonResponse
     */
    public function verify(NoParamRequest $request, int $verifyCode) : JsonResponse
    {
        $user = $request->user();

        if ($user->getVerifyCode() !== $verifyCode) {
            return $this->respondWithError('invalid_verification_code');
        }

        if ($user->getVerifyExpire()->isPast()) {
            return $this->respondWithError('verification_code_expired');
        }

        return $this->respond();
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
        $user = $this->userRepository->findByDeviceId($deviceId);

        $facebookId = ($user->getFacebookId()) ?: '';
        $username = ($user->getUsername()) ?: '';
        $fullName = $user->client->getFullName();

        return $this->respond(compact('username', 'facebookId', 'fullName'));
    }

}