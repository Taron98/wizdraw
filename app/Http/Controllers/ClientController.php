<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wizdraw\Http\Requests\Client\ClientPhoneRequest;
use Wizdraw\Http\Requests\Client\ClientUpdateRequest;
use Wizdraw\Services\ClientService;
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

    /**
     * UserController constructor.
     *
     * @param ClientService $clientService
     * @param SmsService $smsService
     */
    public function __construct(ClientService $clientService, SmsService $smsService)
    {
        $this->clientService = $clientService;
        $this->smsService = $smsService;
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

        return $this->respond($client);
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
        $sms = $this->smsService->sendSms($user->client->getPhone(), $user->getVerifyCode());
        if (!$sms) {
            return $this->respondWithError('problem sending SMS');
        }

        return $this->respond($client);
    }

}