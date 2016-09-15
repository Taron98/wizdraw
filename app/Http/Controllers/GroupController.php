<?php

namespace Wizdraw\Http\Controllers;

use Wizdraw\Http\Requests\Group\GroupCreateUpdateRequest;
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
     * Creating a group route
     *
     * @param GroupCreateUpdateRequest $request
     *
     * @return \Wizdraw\Models\AbstractModel
     */
    public function store(GroupCreateUpdateRequest $request)
    {
        $client = $request->user()->client;

        return $this->groupService->createGroup($request->inputs(), $client);
    }

}
