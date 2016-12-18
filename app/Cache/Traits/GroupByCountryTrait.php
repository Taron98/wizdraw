<?php

namespace Wizdraw\Cache\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Wizdraw\Cache\Entities\AbstractCacheEntity;

/**
 * Class GroupByCountryTrait
 * @package Wizdraw\Cache\Traits
 */
trait GroupByCountryTrait
{

    /**
     * @param Collection $entities
     */
    protected function postSave(Collection $entities)
    {
        parent::postSave($entities);

        $entitiesByCountries = $entities->groupBy(function ($entity) {
            return $entity->getCountryId();
        });

        $entitiesByCountries->each(function (Collection $entitiesCountry, int $countryId) {
            $entitiesIds = $entitiesCountry->map(function (AbstractCacheEntity $entity) {
                return $entity->getId();
            });

            $this->redis->lpush(
                redis_key(self::INDEX_BY_COUNTRY_ID, $countryId),
                $entitiesIds->toArray()
            );
        });
    }

    /**
     * @param int $countryId
     * @param string $sortOrder
     *
     * @return LengthAwarePaginator
     */
    public function findByCountryId(int $countryId, $sortOrder = 'ASC'): LengthAwarePaginator
    {
        return $this->paginate(redis_key(self::INDEX_BY_COUNTRY_ID, $countryId), $sortOrder);
    }

}