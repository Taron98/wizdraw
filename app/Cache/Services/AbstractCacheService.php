<?php

namespace Wizdraw\Cache\Services;

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
     * @return AbstractCacheEntity|null
     */
    public function entityFromArray($data = [])
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
     * @param string|int $key
     *
     * @return string|[]
     */
    public function find($key)
    {
        $entityJson = $this->redis->hgetall(redis_key($this->keyPrefix, $key));

        if (!is_null($entityJson)) {
            return $this->entityFromArray($entityJson);
        }
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
     * @param array $rawEntities
     */
    public function saveFromQueue(array $rawEntities = [])
    {
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
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    abstract public function validate(AbstractCacheEntity $entity);

}