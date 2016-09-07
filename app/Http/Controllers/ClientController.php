<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Wizdraw\Http\Requests\User\UserUpdateRequest;
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
     * @param UserUpdateRequest   $request
     * @param                     $id
     *
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, $id) : JsonResponse
    {
        $input = $request->inputs();

        $this->clientRepository->update($input, $id);
        $client = $this->clientRepository->find($id);

        return response()->json($client);
    }

}