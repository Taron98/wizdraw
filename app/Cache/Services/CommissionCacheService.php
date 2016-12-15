<?php

namespace Wizdraw\Cache\Services;

use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\CommissionCache;
use Wizdraw\Cache\Traits\GroupByCountryTrait;

/**
 * Class CommissionCacheService
 * @package Wizdraw\Cache\Services
 */
class CommissionCacheService extends AbstractCacheService
{
    use GroupByCountryTrait;

    const INDEX_BY_COUNTRY_ID = 'commissions:country';
    const INDEX_SORT_BY = ['BY' => 'commission:*->minRange', 'ALPHA' => false];

    /** @var string */
    protected static $entity = CommissionCache::class;

    /** @var  string */
    protected $keyPrefix = 'commission';

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
        /** @var CommissionCache $entity */
        return true;
    }

    /**
     * @param stdClass $stdJson
     *
     * @return AbstractCacheEntity|null
     */
    public function mapFromQueue(stdClass $stdJson)
    {
        $countryId = $this->countryCacheService->findIdByName($stdJson->country);

        if (is_null($countryId)) {
            return null;
        }

        /** @var CommissionCache $entity */
        $entity = parent::mapFromQueue($stdJson);

        $entity->setCountryId($countryId)
            ->setPercentage($stdJson->percentage)
            ->setConst($stdJson->const)
            ->setMinRange($stdJson->min_range)
            ->setMaxRange($stdJson->max_range);

        return $entity;
    }

}