<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Wizdraw\Http\Requests\Group\GroupCreateUpdateRequest;
use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\Group;
use Wizdraw\Services\GroupService;

/**
 * Class GroupController
 * @package Wizdraw\Http\Controllers
 */
class GroupController extends AbstractController
{
    /** @var  GroupService */
    private $groupService;

    /**
     * GroupController constructor.
     *
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * Showing a group route
     *
     * @param $id
     *
     * @return mixed
     */
    public function show(int $id)
    {
        return $this->groupService->find($id);
    }

    /**
     * Creating a group route
     *
     * @param GroupCreateUpdateRequest $request
     *
     * @return AbstractModel
     */
    public function create(GroupCreateUpdateRequest $request)
    {
        $adminClient = $request->user()->client;
        $groupName = $request->only('name');
        $groupClients = $request->input('clients');

        return $this->groupService->createGroup($adminClient, $groupName, $groupClients);
    }

    /**
     * Updating a group route
     *
     * @param GroupCreateUpdateRequest $request
     * @param Group $group
     *
     * @return AbstractModel
     *
     */
    public function update(GroupCreateUpdateRequest $request, Group $group)
    {
        $client = $request->user()->client;

        if ($client->cannot('update', $group)) {
            return $this->respondWithError('group_not_owned', Response::HTTP_FORBIDDEN);
        }

        $groupClients = $request->input('clients');

        return $this->groupService->updateGroup($group->getId(), $request->inputs(), $groupClients);
    }

}
