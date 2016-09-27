<?php

namespace Wizdraw\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Wizdraw\Models\Client;
use Wizdraw\Models\Group;

/**
 * Class GroupPolicy
 * @package Wizdraw\Policies
 */
class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can show the group
     *
     * @param Client $client
     * @param Group $group
     *
     * @return bool
     */
    public function show(Client $client, Group $group)
    {
        return $client->getId() === $this->getAdminClientId($group);
    }

    /**
     * Determine whether the user can update the group
     *
     * @param Client $client
     * @param Group $group
     *
     * @return bool
     */
    public function update(Client $client, Group $group)
    {
        return $client->getId() === $this->getAdminClientId($group);
    }

    /**
     * @param Group $group
     *
     * @return int
     */
    private function getAdminClientId(Group $group) : int
    {
        $groupClient = $group->adminClient()->first();

        return $groupClient->getId();
    }

}
