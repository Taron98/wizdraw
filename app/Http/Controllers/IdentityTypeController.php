<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Wizdraw\Services\IdentityTypeService;

/**
 * Class IdentityTypeController
 * @package Wizdraw\Http\Controllers
 */
class IdentityTypeController extends AbstractController
{

    /** @var  IdentityTypeService */
    private $identityTypeService;

    /**
     * IdentityTypeController constructor.
     *
     * @param IdentityTypeService $identityTypeService
     */
    public function __construct(IdentityTypeService $identityTypeService)
    {
        $this->identityTypeService = $identityTypeService;
    }

    /**
     * @return JsonResponse
     */
    public function all() : JsonResponse
    {
        $identityTypes = $this->identityTypeService->all();

        return $this->respond($identityTypes);
    }


}