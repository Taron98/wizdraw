<?php

namespace Wizdraw\Http\Controllers;

use Wizdraw\Http\Requests\Group\GroupCreateUpdateRequest;
use Wizdraw\Models\AbstractModel;
use Wizdraw\Services\GroupService;

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
        $client = $request->user()->client;

        return $this->groupService->createGroup($request->inputs(), $client);
    }

    /**
     * Updating a group route
     *
     * @param GroupCreateUpdateRequest $request
     * @param int                      $id
     *
     * @return AbstractModel
     */
    public function update(GroupCreateUpdateRequest $request, int $id)
    {
        return $this->groupService->update($request->inputs(), $id);
    }

}
