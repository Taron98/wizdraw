<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\Response;
use Wizdraw\Cache\Entities\CountryCache;
use Wizdraw\Cache\Services\BankCacheService;
use Wizdraw\Cache\Services\CommissionCacheService;
use Wizdraw\Cache\Services\CountryCacheService;
use Wizdraw\Cache\Services\RateCacheService;
use Wizdraw\Http\Requests\Country\CountryShowByLocationRequest;

/**
 * Class CountryController
 * @package Wizdraw\Http\Controllers
 */
class CountryController extends AbstractController
{
    /** @var  CountryCacheService */
    private $countryCacheService;

    /** @var  RateCacheService */
    private $rateCacheService;

    /** @var  CommissionCacheService */
    private $commissionCacheService;

    /** @var  BankCacheService */
    private $bankCacheService;

    /**
     * GroupController constructor.
     *
     * @param CountryCacheService $countryCacheService
     * @param RateCacheService $rateCacheService
     * @param CommissionCacheService $commissionCacheService
     * @param BankCacheService $bankCacheService
     */
    public function __construct(
        CountryCacheService $countryCacheService,
        RateCacheService $rateCacheService,
        CommissionCacheService $commissionCacheService,
        BankCacheService $bankCacheService
    ) {
        $this->countryCacheService = $countryCacheService;
        $this->rateCacheService = $rateCacheService;
        $this->commissionCacheService = $commissionCacheService;
        $this->bankCacheService = $bankCacheService;
    }

    /**
     * Showing a country route
     *
     * @param int $id
     *
     * @return mixed
     */
    public function show(int $id)
    {
        /** @var CountryCache $country */
        $country = $this->countryCacheService->find($id);

        if (is_null($country)) {
            return $this->respondWithError('country_not_found', Response::HTTP_NOT_FOUND);
        }

        $rate = $this->rateCacheService->find($country->getId());
        $country->setRate($rate);

        $commissions = $this->commissionCacheService->findByCountryId($country->getId());
        $country->setCommissions($commissions);

        return $country;
    }

    /**
     * Showing by location route
     *
     * @param CountryShowByLocationRequest $request
     *
     * @return mixed
     */
    public function showByLocation(CountryShowByLocationRequest $request)
    {
        $inputs = $request->inputs();
        $latitude = $inputs[ 'latitude' ];
        $longitude = $inputs[ 'longitude' ];

        /** @var CountryCache $country */
        $country = $this->countryCacheService->findByLocation($latitude, $longitude);

        if (is_null($country)) {
            return $this->respondWithError('country_not_found', Response::HTTP_NOT_FOUND);
        }

        return $country;
    }

    /**
     * Showing list of countries route
     *
     * @return mixed
     */
    public function list()
    {
        return $this->countryCacheService->allPaginated();
    }

    /**
     * Showing list of bank of the country route
     *
     * @param int $id
     *
     * @return mixed
     */
    public function banks(int $id)
    {
        return $this->bankCacheService->findByCountryId($id);
    }

}
