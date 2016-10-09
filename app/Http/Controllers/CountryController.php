<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\Response;
use Wizdraw\Cache\Services\CountryCacheService;

/**
 * Class CountryController
 * @package Wizdraw\Http\Controllers
 */
class CountryController extends AbstractController
{
    /** @var  CountryCacheService */
    private $countryCacheService;

    /**
     * GroupController constructor.
     *
     * @param CountryCacheService $countryCacheService
     */
    public function __construct(CountryCacheService $countryCacheService)
    {
        $this->countryCacheService = $countryCacheService;
    }

    /**
     * Showing a group route
     *
     * @param int $id
     *
     * @return mixed
     */
    public function show(int $id)
    {
        $country = $this->countryCacheService->find($id);

        if (is_null($country)) {
            return $this->respondWithError('country_not_found', Response::HTTP_NOT_FOUND);
        }

        return $country;
    }

}
