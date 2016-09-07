<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wizdraw\Http\Requests\NoParamRequest;
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