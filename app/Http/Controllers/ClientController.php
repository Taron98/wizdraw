<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wizdraw\Http\Requests\Client\ClientPhoneRequest;
use Wizdraw\Http\Requests\Client\ClientUpdateRequest;
use Wizdraw\Services\ClientService;
use Wizdraw\Services\FileService;
use Wizdraw\Services\SmsService;

/**
 * Class ClientController
 * @package Wizdraw\Http\Controllers
 */
class ClientController extends AbstractController
{

    /** @var  ClientService */
    private $clientService;

    /** @var  SmsService */
    private $smsService;

    /** @var FileService */
    private $fileService;

    /**
     * UserController constructor.
     *
     * @param ClientService $clientService
     * @param SmsService $smsService
     * @param FileService $fileService
     */
    public function __construct(ClientService $clientService, SmsService $smsService, FileService $fileService)
    {
        $this->clientService = $clientService;
        $this->smsService = $smsService;
        $this->fileService = $fileService;
    }

    /**
     * Updating client route
     *
     * @param ClientUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(ClientUpdateRequest $request) : JsonResponse
    {
        $client = $this->clientService->update($request->inputs(), $request->user()->client->getId());

        // todo: refactor
        $profileImage = $request->input('profileImage');
        if (!empty($profileImage)) {
            $uploadStatus = $this->fileService->uploadProfile($client->getId(), $profileImage);

            if (!$uploadStatus) {
                return $this->respondWithError('could_not_upload_profile_image', Response::HTTP_BAD_REQUEST);
            }
        }

        // todo: refactor
        $identityImage = $request->input('identityImage');
        if (!empty($identityImage)) {
            $uploadStatus = $this->fileService->uploadIdentity($client->getId(), $identityImage);

            if (!$uploadStatus) {
                return $this->respondWithError('could_not_upload_identity_image', Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->respond(array_merge($client->toArray(), [
            'identityImage' => $this->fileService->getUrlIfExists(FileService::TYPE_IDENTITY, $client->getId()),
            'profileImage'  => $this->fileService->getUrlIfExists(FileService::TYPE_PROFILE, $client->getId()),
        ]));
    }

    /**
     * Updating phone route
     *
     * @param ClientPhoneRequest $request
     *
     * @return JsonResponse
     */
    public function phone(ClientPhoneRequest $request) : JsonResponse
    {
        $user = $request->user();
        $client = $this->clientService->update($request->inputs(), $request->user()->client->getId());

        // todo: relocation?
3        $sms = $this->smsService->sendSms($user->client->getPhone(), $user->getVerifyCode());
        if (!$sms) {
            return $this->respondWithError('could_not_send_sms');
        }

        return $this->respond($client);
    }

}