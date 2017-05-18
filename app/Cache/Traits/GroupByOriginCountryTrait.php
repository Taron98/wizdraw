<?php

namespace Wizdraw\Cache\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Wizdraw\Cache\Entities\AbstractCacheEntity;

/**
 * Class GroupByOriginCountryTrait
 * @package Wizdraw\Cache\Traits
 */
trait GroupByOriginCountryTrait
{

    /**
     * @param Collection $entities
     */
    protected function postSave(Collection $entities)
    {
        parent::postSave($entities);

        $entitiesByOrigin = $entities->groupBy(function ($entity) {
            return $entity->getOrigin();
        });

        $originToDestination = [];

        foreach($entitiesByOrigin as $key => $val){
            $ent = $val->groupBy(function ($entity) {
                return $entity->getCountryId();
            });
            $originToDestination[$key]=$ent;
        };

        foreach ($originToDestination as $origin => $destination){
            foreach ($destination as $destinationId => $destination){
                $entitiesIds = $destination->map(function (AbstractCacheEntity $entity) {
                    return $entity->getId();
                });

                $this->redis->lpush(
                    redis_key(self::INDEX_BY_ORIGIN_ID, $origin, 'destination', $destinationId),
                    $entitiesIds->toArray()
                );
            }
        }
    }

    /**
     * @param int $countryId
     * @param string $sortOrder
     *
     * @param int $origin
     *
     * @return LengthAwarePaginator
     */
    public function findByCountryId(int $countryId, $sortOrder = 'ASC', int $origin): LengthAwarePaginator
    {
        return $this->paginate(redis_key(self::INDEX_BY_ORIGIN_ID, $origin, 'destination', $countryId), $sortOrder);
    }


}