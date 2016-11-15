<?php

namespace Wizdraw\Cache\Services;

use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\RateCache;

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
        $countryId = $this->countryCacheService->findCountryIdByName($stdJson->country);

        /** @var RateCache $entity */
        $entity = parent::mapFromQueue($stdJson);

        $entity->setCountryId($countryId)
            ->setRate($stdJson->rate);

        return $entity;
    }

}