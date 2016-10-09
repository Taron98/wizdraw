<?php

namespace Wizdraw\Cache\Traits;

use Illuminate\Support\Collection;
use Wizdraw\Cache\Entities\AbstractCacheEntity;

/**
 * Class GroupByCountryTrait
 * @package Wizdraw\Cache\Traits
 */
trait GroupByCountryTrait
{

    /**
     * @param AbstractCacheEntity $entity
     */
    protected function postSave(AbstractCacheEntity $entity)
    {
        $this->redis->lpush(
            redis_key(self::INDEX_BY_COUNTRY_ID, $entity->getCountryId()),
            [$entity->getId()]
        );
    }

    /**
     * @param int $countryId
     *
     * @return Collection
     */
    public function findIdsByCountryId(int $countryId) : Collection
    {
        $entityIds = $this->redis->lrange(redis_key(self::INDEX_BY_COUNTRY_ID, $countryId), 0, -1);

        return collect($entityIds);
    }

    /**
     * @param int $countryId
     *
     * @return Collection
     */
    public function findByCountryId(int $countryId) : Collection
    {
        $entityIds = $this->findIdsByCountryId($countryId);

        $entities = $entityIds->map(function ($entityId) {
            return $this->find($entityId);
        });

        return $entities;
    }

}