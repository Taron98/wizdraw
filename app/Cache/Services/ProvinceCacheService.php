<?php

namespace Wizdraw\Cache\Services;

use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\ProvinceCache;

/**
 * Class ProvinceCacheService
 * @package Wizdraw\Cache\Services
 */
class ProvinceCacheService extends AbstractCacheService
{
    /** @var string */
    protected static $entity = ProvinceCache::class;

    /** @var  string */
    protected $keyPrefix = 'province';

    /**
     * ProvinceCacheService constructor
     *
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        parent::__construct($redis);
    }

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    public function validate(AbstractCacheEntity $entity)
    {
        /** @var ProvinceCache $entity */
        return !(empty($entity->getName()));
    }

    /**
     * @param stdClass $stdJson
     *
     * @return AbstractCacheEntity|null
     */
    public function mapFromQueue(stdClass $stdJson)
    {
        /** @var ProvinceCache $entity */
        $entity = parent::mapFromQueue($stdJson);

        $entity->setCountryId($stdJson->country_id)
               ->setName($stdJson->name);

        return $entity;
    }


}