<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Wizdraw\Http\Requests\User\UpdateClientRequest;
use Wizdraw\Repositories\ClientRepository;

/**
 * Class ClientController
 * @package Wizdraw\Http\Controllers
 */
class ClientController extends AbstractController
{

    /** @var  ClientRepository */
    private $clientRepository;

    /**
     * UserController constructor.
     *
     * @param ClientRepository $clientRepository
     *
     */
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * Updating client route
     *
     * @param UpdateClientRequest $request
     * @param                     $id
     *
     * @return JsonResponse
     */
    public function update(UpdateClientRequest $request, $id) : JsonResponse
    {
        $input = $request->inputs();

        $this->clientRepository->update($input, $id);
        $client = $this->clientRepository->find($id);

        return response()->json($client);
    }

}