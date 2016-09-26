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
     * @param Client $adminClient
     * @param array $attributes
     * @param array $groupClients
     *
     * @return AbstractModel
     */
    public function createGroup(Client $adminClient, array $attributes, array $groupClients = []) : AbstractModel
    {
        $memberClients = $this->clientService->createClients($groupClients);
        $memberClientsIds = $memberClients->pluck('id')->toArray();
        $group = $this->repository->createWithRelation($adminClient, $attributes, $memberClientsIds);

        return $group;
    }

}