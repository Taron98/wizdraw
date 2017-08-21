<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\Response;
use Wizdraw\Cache\Entities\CountryCache;
use Wizdraw\Cache\Services\BankCacheService;
use Wizdraw\Cache\Services\BranchCacheService;
use Wizdraw\Cache\Services\CommissionCacheService;
use Wizdraw\Cache\Services\CountryCacheService;
use Wizdraw\Cache\Services\RateCacheService;
use Wizdraw\Http\Requests\Country\CountryShowByLocationRequest;
use Wizdraw\Http\Requests\Country\CountryStoresRequest;
use Wizdraw\Http\Requests\NoParamRequest;

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

    /** @var  BranchCacheService */
    private $branchCacheService;

    /**
     * GroupController constructor.
     *
     * @param CountryCacheService $countryCacheService
     * @param RateCacheService $rateCacheService
     * @param CommissionCacheService $commissionCacheService
     * @param BankCacheService $bankCacheService
     * @param BranchCacheService $branchCacheService
     */
    public function __construct(
        CountryCacheService $countryCacheService,
        RateCacheService $rateCacheService,
        CommissionCacheService $commissionCacheService,
        BankCacheService $bankCacheService,
        BranchCacheService $branchCacheService
    ) {
        $this->countryCacheService = $countryCacheService;
        $this->rateCacheService = $rateCacheService;
        $this->commissionCacheService = $commissionCacheService;
        $this->bankCacheService = $bankCacheService;
        $this->branchCacheService = $branchCacheService;
    }

    /**
     * Showing a country route
     *
     * @param int $id
     *
     * @param NoParamRequest $request
     *
     * @return mixed
     */
    public function show(int $id, NoParamRequest $request)
    {
        $client = $request->user()->client;


        /** @var CountryCache $country */
        $country = $this->countryCacheService->find($id);

        if (is_null($country)) {

            $resInputs = ['id' => $id];

            return $this->respondWithError('country_not_found', Response::HTTP_NOT_FOUND, $resInputs);
        }

        /* get NIS rates if necessary for request made from israel application */
        $this->rateCacheService->setKeyPrefix($request);
        $rate = $this->rateCacheService->find($country->getId());
        $country->setRate($rate);

        $commissions = $this->commissionCacheService->findByCountryId($country->getId(), 'ASC',
            $client->defaultCountryId);
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
            return $this->respondWithError('country_not_found', Response::HTTP_NOT_FOUND, $inputs);
        }

        return $country;
    }

    /**
     * Showing list of countries route
     *
     * @param NoParamRequest $request
     *
     * @return mixed
     */
    public function list(NoParamRequest $request)
    {
        $user = $request->user();
        if (is_null($user)) {
            return $this->countryCacheService->allPaginated();
        } else {
            return $this->countryCacheService->activeCountriesForOrigin($user->client->getDefaultCountry());
        }
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

    /**
     * showing list of branches of the selected bank
     *
     * @param int $id
     *
     * @return mixed
     */
    public function branches(int $id)
    {
        return $this->branchCacheService->findByBankId($id);
    }

    /**
     * @param NoParamRequest $request
     *
     * @return array
     */
    public function stores(NoParamRequest $request)
    {
        $countryId = $request->input('countryId');
        return get_country_stores($countryId);
    }
}
