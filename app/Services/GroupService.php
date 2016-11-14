<?php

namespace Wizdraw\Services;

use Illuminate\Support\Collection;
use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\Client;
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
        $memberClientsIds = [];

        if (is_array($groupClients)) {
            $memberClients = $this->clientService->createClients($groupClients);
            $memberClientsIds = $memberClients->pluck('id')->toArray();
        }

        $group = $this->repository->createWithRelation($adminClient, $attributes, $memberClientsIds);

        return $group;
    }

    /**
     * @param int $id
     * @param array $attributes
     * @param array $groupClients
     *
     * @return AbstractModel
     */
    public function updateGroup(int $id, array $attributes, $groupClients = [])
    {
        $memberClientsIds = [];

        if (!is_null($groupClients)) {
            $memberClients = $this->clientService->createClients($groupClients);
            $memberClientsIds = $memberClients->pluck('id')->toArray();
        }

        $group = $this->repository->updateWithRelation($id, $attributes, $memberClientsIds);

        return $group;
    }

}