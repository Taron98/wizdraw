<?php

namespace Wizdraw\Services;

use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\Client;
use Wizdraw\Models\Group;
use Wizdraw\Repositories\GroupRepository;

/**
 * Class GroupService
 * @package Wizdraw\Services
 */
class GroupService extends AbstractService
{

    /** @var  ClientService */
    protected $clientService;

    /**
     * GroupService constructor.
     *
     * @param GroupRepository $groupRepository
     * @param ClientService $clientService
     */
    public function __construct(
        GroupRepository $groupRepository,
        ClientService $clientService
    ) {
        $this->repository = $groupRepository;
        $this->clientService = $clientService;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->repository->with('memberClients')->find($id);
    }

    /**
     * @param Client $adminClient
     * @param array $attributes
     * @param array $groupClients
     *
     * @return AbstractModel
     */
    public function createGroup(Client $adminClient, array $attributes, $groupClients = []) : AbstractModel
    {
        $groupClientIds = [];

        if (is_array($groupClients)) {
            $memberClients = $this->clientService->createClients($groupClients);
            $groupClientIds = $memberClients->pluck('id')->toArray();
        }

        $group = $this->repository->createWithRelation($adminClient, $attributes, $groupClientIds);

        return $group;
    }

    /**
     * @param Group $group
     * @param array $groupClients
     *
     * @return AbstractModel
     */
    public function addClient(Group $group, $groupClients = [])
    {
        $groupClientIds = [];

        if (!is_null($groupClients)) {
            $memberClients = $this->clientService->createClients($groupClients);
            $groupClientIds = $memberClients->pluck('id')->toArray();
        }

        $group = $this->repository->addClient($group, $groupClientIds);

        return $group;
    }

    /**
     * @param Group $group
     * @param array $groupClientIds
     *
     * @return AbstractModel
     */
    public function removeClient(Group $group, $groupClientIds = [])
    {
        $group = $this->repository->removeClient($group, $groupClientIds);

        return $group;
    }

}