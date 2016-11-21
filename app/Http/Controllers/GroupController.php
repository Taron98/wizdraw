<?php

namespace Wizdraw\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Wizdraw\Http\Requests\Group\GroupAddClientRequest;
use Wizdraw\Http\Requests\Group\GroupCreateRequest;
use Wizdraw\Http\Requests\Group\GroupRemoveClientRequest;
use Wizdraw\Http\Requests\Group\GroupUpdateRequest;
use Wizdraw\Http\Requests\NoParamRequest;
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
     * @param NoParamRequest $request
     * @param Group $group
     *
     * @return mixed
     */
    public function show(NoParamRequest $request, Group $group)
    {
        $adminClient = $request->user()->client;

        if ($adminClient->cannot('show', $group)) {
            return $this->respondWithError('group_not_owned', Response::HTTP_FORBIDDEN);
        }

        // todo: change that when we insert admin client id into the group clients
        /** @var Group $group */
        $group = $this->groupService->find($group->getId());
        $myself = $request->user()->client()->with('bankAccounts')->first();
        $group->memberClients->put(null, $myself);

        return $group;
    }

    /**
     * Showing list of groups route
     *
     * @param NoParamRequest $request
     *
     * @return mixed
     */
    public function list(NoParamRequest $request)
    {
        $adminClient = $request->user()->client;

        return $this->respond($adminClient->adminGroups);
    }

    /**
     * Creating a group route
     *
     * @param GroupCreateRequest $request
     *
     * @return AbstractModel
     */
    public function create(GroupCreateRequest $request)
    {
        $adminClient = $request->user()->client;
        $groupName = $request->only('name');
        $groupClients = $request->input('clients');

        return $this->groupService->createGroup($adminClient, $groupName, $groupClients);
    }

    /**
     * Updating a group route
     *
     * @param GroupUpdateRequest $request
     * @param Group $group
     *
     * @return AbstractModel
     */
    public function update(GroupUpdateRequest $request, Group $group)
    {
        $adminClient = $request->user()->client;

        if ($adminClient->cannot('update', $group)) {
            return $this->respondWithError('group_not_owned', Response::HTTP_FORBIDDEN);
        }

        return $this->groupService->update($request->inputs(), $group->getId());
    }

    /**
     * @param GroupAddClientRequest $request
     * @param Group $group
     *
     * @return AbstractModel
     */
    public function addClient(GroupAddClientRequest $request, Group $group) : AbstractModel
    {
        $adminClient = $request->user()->client;

        if ($adminClient->cannot('addClient', $group)) {
            return $this->respondWithError('group_not_owned', Response::HTTP_FORBIDDEN);
        }

        $groupClients = $request->input('clients');

        return $this->groupService->addClient($group, $groupClients);
    }

    /**
     * @param GroupRemoveClientRequest $request
     * @param Group $group
     *
     * @return AbstractModel
     */
    public function removeClient(GroupRemoveClientRequest $request, Group $group) : AbstractModel
    {
        $adminClient = $request->user()->client;

        if ($adminClient->cannot('removeClient', $group)) {
            return $this->respondWithError('group_not_owned', Response::HTTP_FORBIDDEN);
        }

        $groupClientIds = $request->input('clients');

        return $this->groupService->removeClient($group, $groupClientIds);
    }

}
