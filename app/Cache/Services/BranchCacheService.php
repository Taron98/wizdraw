<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/15/2017
 * Time: 10:47
 */

namespace Wizdraw\Cache\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\BranchCache;
use Predis\Client;

/**
 * Class BranchCacheService
 * @package Wizdraw\Cache\Services
 */
class BranchCacheService extends AbstractCacheService
{
    const INDEX_BY_BANK = 'branches:bank';
    const INDEX_SORT_BY = ['BY' => 'branch:*->name', 'ALPHA' => true];
    const INDEX_ALL = 'branches';
    const INDEX_BY_ID = 'branch';

    /** @var string */
    protected static $entity = BranchCache::class;

    /** @var  string */
    protected $keyPrefix = 'branch';

    /** @var  BankCacheService */
    protected $bankCacheService;

    /**
     * CountryCacheService constructor
     *
     * @param Client $redis
     * @param BankCacheService $bankCacheService
     */
    public function __construct(Client $redis, BankCacheService $bankCacheService)
    {
        parent::__construct($redis);

        $this->bankCacheService = $bankCacheService;
    }

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    public function validate(AbstractCacheEntity $entity)
    {
        /** @var BranchCache $entity */
        return !(empty($entity->getBank()) || empty($entity->getIfsc())
            || empty($entity->getBranch()) || empty($entity->getAddress())
            || empty($entity->getCity()) || empty($entity->getDistrict())
            || empty($entity->getState()));
    }

    /**
     * @param stdClass $stdJson
     *
     * @return AbstractCacheEntity|null
     */
    public function mapFromQueue(stdClass $stdJson)
    {
        $bankId = $this->bankCacheService->findIdByName($stdJson->bank);

        /** @var BranchCache $entity */
        $entity = parent::mapFromQueue($stdJson);

        $entity->setBankId($bankId)
            ->setBranchId($stdJson->id)
            ->setBank($stdJson->bank)
            ->setIfsc($stdJson->ifsc)
            ->setBranch($stdJson->branch)
            ->setAddress($stdJson->address)
            ->setCity($stdJson->city)
            ->setDistrict($stdJson->district)
            ->setState($stdJson->state);

        return $entity;

    }

    /**
     * @param Collection $branches
     */
    protected function postSave(Collection $branches)
    {
        parent::postSave($branches);

        $banks = $branches->groupBy(function ($entity) {
            return $entity->getBankId();
        });

        $banks->each(function (Collection $entitiesBank, int $bankId) {
            $entitiesIds = $entitiesBank->map(function (AbstractCacheEntity $entity) {
                return $entity->getId();
            });

            $this->redis->lpush(
                redis_key(self::INDEX_BY_BANK, $bankId),
                $entitiesIds->toArray()
            );
        });
    }

    /**
     * @param int $id
     * @param string $sortOrder
     *
     * @return LengthAwarePaginator
     */
    public function findByBankId(int $id, $sortOrder = 'ASC'): LengthAwarePaginator
    {
        return $this->paginate(redis_key(self::INDEX_BY_BANK, $id), $sortOrder);
    }
}