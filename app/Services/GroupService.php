<?php

namespace Wizdraw\Services;

use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\Client;
use Wizdraw\Repositories\GroupRepository;

/**
 * Class GroupService
 * @package Wizdraw\Services
 */
class GroupService extends AbstractService
{

    /**
     * GroupService constructor.
     *
     * @param GroupRepository $groupRepository
     */
    public function __construct(GroupRepository $groupRepository)
    {
        $this->repository = $groupRepository;
    }

    /**
     * @param array  $data
     * @param Client $client
     *
     * @return AbstractModel
     */
    public function createGroup(array $data, Client $client) : AbstractModel
    {
        return $this->repository->createWithRelation($data, $client);
    }

}