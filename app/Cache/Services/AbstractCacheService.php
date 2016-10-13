<?php

namespace Wizdraw\Cache\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;

/**
 * Class AbstractCacheService
 * @package Wizdraw\Cache\Services
 */
abstract class AbstractCacheService
{
    const PAGE_NAME = 'page';

    /** @var  Client */
    protected $redis;

    /** @var string */
    protected static $entity;

    /** @var  string */
    protected $keyPrefix;

    /**
     * AbstractCacheTable constructor
     *
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param $data
     *
     * @return null|AbstractCacheEntity
     */
    public function entityFromArray($data)
    {
        if (!is_array($data) || empty($data)) {
            return null;
        }

        /** @var AbstractCacheEntity $entity */
        $entity = new static::$entity($data);

        if (isset($data[ 'id' ])) {
            $entity->setId($data[ 'id' ]);
        }

        return $entity;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function all() : LengthAwarePaginator
    {
        $entitiesKeys = $this->redis->keys(redis_key($this->keyPrefix, '*'));
        $entities = $this->transformIdsToEntities($entitiesKeys);

        return $this->paginate($entities);
    }

    /**
     * @param string|int $key
     *
     * @return null|AbstractCacheEntity
     */
    public function find($key)
    {
        $entityJson = $this->redis->hgetall(redis_key($this->keyPrefix, $key));

        if (is_null($entityJson)) {
            return null;
        }

        $entity = $this->entityFromArray($entityJson);

        return $entity;
    }

    /**
     * Save keys for multiple hashes
     *
     * @param Collection $cacheEntities
     *
     * @return int
     *
     */
    public function save(Collection $cacheEntities)
    {
        /** @var Collection $cleanedEntities */
        $cleanedEntities = $this->cleanEntities($cacheEntities);

        $cleanedEntities->each(function ($cleanedEntity) {
            $this->redis->hmset(
                redis_key($this->keyPrefix, $cleanedEntity->getKey()),
                $cleanedEntity->toArray(false)
            );

            // Call the method if defined, to run after saving an entity
            if (method_exists($this, 'postSave')) {
                $this->postSave($cleanedEntity);
            }
        });
    }

    /**
     * @param Collection $entities
     *
     * @return Collection
     */
    private function cleanEntities(Collection $entities) : Collection
    {
        // Remove invalid data
        $entities = $entities->filter(function (AbstractCacheEntity $entity) {
            return $this->validate($entity);
        });

        $entities = $entities->mapWithKeys(function (AbstractCacheEntity $entity) {
            return [$entity->getId() => $entity];
        });

        return $entities;
    }

    /**
     * @param string $data
     *
     * @return null
     */
    public function saveFromQueue($data = '')
    {
        if (empty($data)) {
            return null;
        }

        $rawEntities = json_decode($data);
        $entities = new Collection();

        foreach ($rawEntities as $rawEntity) {
            $entity = $this->mapFromQueue($rawEntity);

            if (!is_null($entity)) {
                $entities->push($entity);
            }
        }

        $this->save($entities);
    }

    /**
     * @param stdClass $stdJson
     *
     * @return AbstractCacheEntity|null
     */
    public function mapFromQueue(stdClass $stdJson)
    {
        /** @var AbstractCacheEntity $entity */
        $entity = new static::$entity();
        $entity->setId($stdJson->id);

        return $entity;
    }

    /**
     * @param array $entityKeys
     *
     * @return Collection
     */
    private function transformIdsToEntities(array $entityKeys = []) : Collection
    {
        $entities = collect($entityKeys)->map(function ($entityKey) {
            return $this->find(redis_unkey($entityKey));
        });

        return $entities;
    }

    /**
     * @param Collection $entities
     *
     * @return LengthAwarePaginator
     */
    protected function paginate(Collection $entities) : LengthAwarePaginator
    {
        $page = Paginator::resolveCurrentPage(self::PAGE_NAME);
        $perPage = config('cache.pagination.perPage');

        $total = $entities->count();
        $paginateEntities = $entities->forPage($page, $perPage);

        $paginator = new LengthAwarePaginator($paginateEntities, $total, $perPage, $page, [
            'path'     => Paginator::resolveCurrentPath(),
            'pageName' => self::PAGE_NAME,
        ]);

        // todo: camel case?
        return $paginator;
    }

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    abstract public function validate(AbstractCacheEntity $entity);

}