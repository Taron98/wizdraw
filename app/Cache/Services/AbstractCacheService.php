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
     * @return Collection
     */
    public function all(): Collection
    {
        if (defined('static::INDEX_ALL')) {
            return $this->sort(static::INDEX_ALL);
        }

        return $this->keys();
    }

    /**
     * @param string $sortOrder
     *
     * @return LengthAwarePaginator|null
     */
    public function allPaginated($sortOrder = 'ASC')
    {
        if (!defined('static::INDEX_ALL')) {
            return null;
        }

        return $this->paginate(static::INDEX_ALL, $sortOrder);
    }

    /**
     * @param string|int $id
     *
     * @return null|AbstractCacheEntity
     */
    public function find($id)
    {
        $entityJson = $this->redis->hgetall(redis_key($this->keyPrefix, $id));

        if (is_null($entityJson)) {
            return null;
        }

        $entity = $this->entityFromArray($entityJson);

        return $entity;
    }

    /**
     * @param string|int $id
     *
     * @return bool
     */
    public function exists($id): bool
    {
        return $this->redis->exists(redis_key($this->keyPrefix, $id));
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
        });

        // Call the method if defined, to run after saving an entity
        if (method_exists($this, 'postSave')) {
            $this->postSave($cleanedEntities);
        }
    }

    /**
     * @param Collection $entities
     */
    protected function postSave(Collection $entities)
    {
        if (defined('static::INDEX_ALL')) {
            $entityIds = $entities->map(function (AbstractCacheEntity $entity) {
                return $entity->getId();
            });

            $this->redis->lpush(static::INDEX_ALL, $entityIds->toArray());
        }
    }

    /**
     * @param Collection $entities
     *
     * @return Collection
     */
    private function cleanEntities(Collection $entities): Collection
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

        if (!empty($stdJson->id)) {
            $entity->setId($stdJson->id);
        }

        return $entity;
    }


    /**
     * @param string $key
     * @param string $sortOrder
     *
     * @return LengthAwarePaginator
     */
    public function paginate(string $key, string $sortOrder): LengthAwarePaginator
    {
        $perPage = config('cache.pagination.perPage');
        $startIndex = $perPage * (Paginator::resolveCurrentPage() - 1);

        $total = $this->total($key);

        // Get current page's items sorted asc
        $entities = $this->sort(
            $key,
            $sortOrder,
            [$startIndex, $perPage]
        );

        $paginator = new LengthAwarePaginator($entities, $total, $perPage, null, [
            'path' => Paginator::resolveCurrentPath(),
        ]);

        return $paginator;
    }

    /**
     * @param string $key
     *
     * @return int
     */
    protected function total(string $key)
    {
        $count = $this->redis->llen($key);

        return $count;
    }

    /**
     * @param string $key
     * @param string $sortOrder
     * @param array $limit
     *
     * @return Collection
     */
    protected function sort(string $key, $sortOrder = 'ASC', $limit = [0, -1])
    {
        $entityIds = $this->redis->sort(
            $key,
            array_merge([
                'SORT'  => $sortOrder,
                'LIMIT' => $limit,
            ], static::INDEX_SORT_BY)
        );
        $entities = $this->transformIdsToEntities($entityIds);

        return $entities;
    }

    /**
     * @return Collection
     */
    protected function keys()
    {
        $entityKeys = $this->redis->keys(redis_key($this->keyPrefix, '*'));
        $entities = $this->transformIdsToEntities($entityKeys);

        return $entities;
    }

    /**
     * @param array $entityKeys
     *
     * @return Collection
     */
    protected function transformIdsToEntities(array $entityKeys = []): Collection
    {
        $entities = collect($entityKeys)->map(function ($entityKey) {
            return $this->find(redis_unkey($entityKey));
        });

        return $entities;
    }

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    abstract public function validate(AbstractCacheEntity $entity);

}