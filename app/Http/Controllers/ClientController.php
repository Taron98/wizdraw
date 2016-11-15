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
        $clientId = $request->user()->client->getId();
        $client = $this->clientService->update($request->inputs(), $clientId);

        // todo: refactor
        $profileImage = $request->input('profileImage');
        if (!empty($profileImage)) {
            $uploadStatus = $this->fileService->uploadProfile($clientId, $profileImage);

            if (!$uploadStatus) {
                return $this->respondWithError('could_not_upload_profile_image', Response::HTTP_BAD_REQUEST);
            }
        }

        // todo: refactor
        $identityImage = $request->input('identityImage');
        if (!empty($identityImage)) {
            $uploadStatus = $this->fileService->uploadIdentity($clientId, $identityImage);

            if (!$uploadStatus) {
                return $this->respondWithError('could_not_upload_identity_image', Response::HTTP_BAD_REQUEST);
            }
        }

        // todo: refactor
        $addressImage = $request->input('addressImage');
        if (!empty($identityImage)) {
            $uploadStatus = $this->fileService->uploadAddress($clientId, $addressImage);

            if (!$uploadStatus) {
                return $this->respondWithError('could_not_upload_address_image', Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->respond(array_merge($client->toArray(), [
            'identityImage' => $this->fileService->getUrlIfExists(FileService::TYPE_IDENTITY, $clientId),
            'addressImage'  => $this->fileService->getUrlIfExists(FileService::TYPE_ADDRESS, $clientId),
            'profileImage'  => $this->fileService->getUrlIfExists(FileService::TYPE_PROFILE, $clientId),
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
        $client = $this->clientService->update($request->inputs(), $user->client->getId());
        \Log::error('Got an error phone: ' . print_r($client->getPhone(), true));
        \Log::error('Got an error verify: ' . print_r($user->getVerifyCode(), true));
        // todo: relocation?
        $sms = $this->smsService->sendSmsNewClient($client->getPhone(), $user->getVerifyCode(), true);
        if (!$sms) {
            return $this->respondWithError('could_not_send_sms');
        }

        return $this->respond($client);
    }

}