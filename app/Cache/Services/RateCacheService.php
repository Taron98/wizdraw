<?php

namespace Wizdraw\Cache\Services;

use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\RateCache;
use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class RateCacheService
 * @package Wizdraw\Cache\Services
 */
class RateCacheService extends AbstractCacheService
{

    /** @var string */
    protected static $entity = RateCache::class;

    /** @var  string */
    protected $keyPrefix = 'rate';

    /** @var  CountryCacheService */
    protected $countryCacheService;

    /**
     * RateCacheService constructor
     *
     * @param Client $redis
     * @param CountryCacheService $countryCacheService
     */
    public function __construct(Client $redis, CountryCacheService $countryCacheService)
    {
        parent::__construct($redis);

        $this->countryCacheService = $countryCacheService;
    }

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    public function validate(AbstractCacheEntity $entity)
    {
        /** @var RateCache $entity */
        return !(empty($entity->getCountryId()) || empty($entity->getRate()));
    }

    /**
     * @param stdClass $stdJson
     *
     * @return AbstractCacheEntity|null
     */
    public function mapFromQueue(stdClass $stdJson)
    {
        $countryId = $this->countryCacheService->findIdByName($stdJson->country);

        /** @var RateCache $entity */
        $entity = parent::mapFromQueue($stdJson);

        $entity->setCountryId($countryId)
            ->setRate($stdJson->rate);

        return $entity;
    }

    /**
     * @param AbstractRequest $request
     */
    public function setKeyPrefix(AbstractRequest $request)
    {
        $client = $request->user()->client;
        $country = $client->getAttribute('default_country_id');
        if($country == 13){
            $this->keyPrefix = 'rateNis';
        }
        elseif($country == 119){
            $this->keyPrefix = 'rateTwd';
        }
    }

    public function rateForUsdRate()
    {
        $wizdrawRate = json_decode($this->redis->get('ilsUsdRate'));
        return $wizdrawRate;

        $stdClass = new stdClass();

        /** @var RateCache $entity */
        $entity = parent::mapFromQueue($stdClass);

        $entity->setRate($wizdrawRate->wf_exchange_rate);

        return $entity;
    }
}