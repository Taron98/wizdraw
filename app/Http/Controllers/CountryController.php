<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\Response;
use Wizdraw\Cache\Entities\CountryCache;
use Wizdraw\Cache\Services\CommissionCacheService;
use Wizdraw\Cache\Services\CountryCacheService;
use Wizdraw\Cache\Services\RateCacheService;

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

    /**
     * GroupController constructor.
     *
     * @param CountryCacheService $countryCacheService
     * @param RateCacheService $rateCacheService
     * @param CommissionCacheService $commissionCacheService
     */
    public function __construct(
        CountryCacheService $countryCacheService,
        RateCacheService $rateCacheService,
        CommissionCacheService $commissionCacheService
    ) {
        $this->countryCacheService = $countryCacheService;
        $this->rateCacheService = $rateCacheService;
        $this->commissionCacheService = $commissionCacheService;
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
     * Showing list of countries route
     *
     * @return mixed
     */
    public function list()
    {
        return $this->countryCacheService->all();
    }

}
