<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wizdraw\Http\Requests\Client\ClientPhoneRequest;
use Wizdraw\Http\Requests\Client\ClientUpdateRequest;
use Wizdraw\Http\Requests\NoParamRequest;
use Wizdraw\Models\Client;
use Wizdraw\Notifications\ClientMissingInfo;
use Wizdraw\Notifications\ClientVerify;
use Wizdraw\Services\AffiliateService;
use Wizdraw\Services\ClientService;
use Wizdraw\Services\FileService;
use Wizdraw\Services\UserService;
use Wizdraw\Services\VipService;

/**
 * Class ClientController
 * @package Wizdraw\Http\Controllers
 */
class ClientController extends AbstractController
{

    /** @var  ClientService */
    private $clientService;

    /** @var  UserService */
    private $userService;

    /** @var VipService */
    private $vipService;

    /** @var FileService */
    private $fileService;

    /** @var AffiliateService */
    private $affiliateService;

    /**
     * UserController constructor.
     *
     * @param ClientService $clientService
     * @param UserService $userService
     * @param VipService $vipService
     * @param FileService $fileService
     * @param AffiliateService $affiliateService
     */
    public function __construct(
        ClientService $clientService,
        UserService $userService,
        VipService $vipService,
        FileService $fileService,
        AffiliateService $affiliateService
    ) {
        $this->clientService = $clientService;
        $this->userService = $userService;
        $this->vipService = $vipService;
        $this->fileService = $fileService;
        $this->affiliateService = $affiliateService;
    }

    /**
     * Updating client route
     *
     * @param ClientUpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update(ClientUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $clientId = $request->user()->client->getId();
        $inputs = $request->inputs();
        $phone = $request->input('phone');
        $birthDate = $request->input('birthDate');
        Log::info('birth date:' . $birthDate);
        $fixedBirth = $this->handleBirthDate($birthDate);
        $inputs['birth_date'] = $fixedBirth;
        $isSetup = !$user->client->isDidSetup();

        Log::info('phone: ' . $phone);

        if (!is_null($phone) || $phone!='') {
            if ($this->clientService->findByPhone($phone)) {
                return $this->respondWithError('phone_already_used', Response::HTTP_BAD_REQUEST);
            }
        }

        /** @var Client $client */
        $client = $this->clientService->update($inputs, $clientId);

        if (is_null($client)) {
            return $this->respondWithError('could_not_create_client', Response::HTTP_BAD_REQUEST);
        }

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
        if (!empty($addressImage)) {
            $uploadStatus = $this->fileService->uploadAddress($clientId, $addressImage);

            if (!$uploadStatus) {
                return $this->respondWithError('could_not_upload_address_image', Response::HTTP_BAD_REQUEST);
            }
        }

        // todo: move to other place
        if ($isSetup) {
            $user->notify(
                (new ClientMissingInfo())
                    ->delay($client->getTargetTime(ClientMissingInfo::REMIND_TIME), $user)
            );

            $this->vipService->createVip($client);
        }

        // Quick fix: For some reason, vip isn't shown in the client
        if (!is_null($client->vip)) {
            $client->vip->fresh();
        }

        return $this->respond(array_merge($client->toArray(), [
            'identityImage' => $this->fileService->getUrlIfExists(FileService::TYPE_IDENTITY, $clientId),
            'addressImage'  => $this->fileService->getUrlIfExists(FileService::TYPE_ADDRESS, $clientId),
            'profileImage'  => $this->fileService->getUrlIfExists(FileService::TYPE_PROFILE, $clientId),
        ]));
    }

    /**
     * @param $birth
     *
     * @return string
     */
    function handleBirthDate($birth)
    {
        $birthParts = explode('-', $birth);
        if (strlen($birthParts[ 0 ]) == 4) {
            return $birth;
        } else {
            return $birthParts[2].'-'.$birthParts[1].'-'.$birthParts[0];
        }
    }

    /**
     * Updating phone route
     *
     * @param ClientPhoneRequest $request
     *
     * @return JsonResponse
     */
    public function phone(ClientPhoneRequest $request): JsonResponse
    {
        $user = $request->user();
        $phone = $request->input('phone');

        if ($this->clientService->findByPhone($phone)) {
            return $this->respondWithError('phone_already_used', Response::HTTP_BAD_REQUEST);
        }

        $client = $this->clientService->update($request->inputs(), $user->client->getId());
        $this->userService->generateVerifyCode($user);

        $user->client->notify(new ClientVerify(true));

        return $this->respond($client);
    }

    /**
     * Add affiliate code for user
     *
     * @param NoParamRequest $request
     * @param $affiliateCode
     *
     * @return mixed
     */
    public function affiliate(NoParamRequest $request, $affiliateCode)
    {
        $client = $request->user()->client;
        $affiliate = $this->affiliateService->findByCode($affiliateCode);

        if (is_null($affiliate)) {
            return $this->respondWithError('affiliate_code_not_found', Response::HTTP_NOT_FOUND);
        }

        $this->clientService->updateAffiliate($affiliate, $client);

        return $affiliate;
    }

}