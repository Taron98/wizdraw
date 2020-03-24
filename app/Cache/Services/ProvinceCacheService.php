<?php

namespace Wizdraw\Cache\Services;

use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Illuminate\Support\Collection;
use Wizdraw\Cache\Entities\ProvinceCache;
use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class ProvinceCacheService
 * @package Wizdraw\Cache\Services
 */
class ProvinceCacheService extends AbstractCacheService
{
    const INDEX = 'provinces';

    /** @var string */
    protected static $entity = ProvinceCache::class;

    /** @var  CountryCacheService */
    protected $provinceCacheService;

    /**
     * ProvinceCacheService constructor
     *
     * @param Client $redis
     * @param ProvinceCacheService $provinceCacheService
     */
    public function __construct(Client $redis, ProvinceCacheService $provinceCacheService)
    {
        parent::__construct($redis);

        $this->provinceCacheService = $provinceCacheService;
    }

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    public function validate(AbstractCacheEntity $entity)
    {
        /** @var ProvinceCache $entity */
        return !(empty($entity->getProvince()));
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

        $entity->setProvince($stdJson->name);
    var_dump($entity);
        return $entity;
    }

    /**
     * @param Collection $provinces
     */
    protected function postSave(Collection $provinces)
    {
        parent::postSave($provinces);

        $provincesArr = $provinces->map(function (ProvinceCache $province) {
            return $province->getProvince();
        });

        $this->redis->lpush(self::INDEX, $provincesArr->toArray());
    }


}