<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wizdraw\Http\Requests\Client\ClientPhoneRequest;
use Wizdraw\Http\Requests\Client\ClientUpdateRequest;
use Wizdraw\Services\ClientService;

/**
 * Class ClientController
 * @package Wizdraw\Http\Controllers
 */
class ClientController extends AbstractController
{

    /** @var  ClientService */
    private $clientService;

    /**
     * UserController constructor.
     *
     * @param ClientService $clientService
     *
     */
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
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
        $client = $this->clientService->update($request->inputs(), $request->user()->client->getId());

        return $this->respond($client);
    }

}