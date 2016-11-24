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
        return $client->getId() === $group->adminClient->getId();
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
        return $client->getId() === $group->adminClient->getId();
    }

    /**
     * Determine whether the user can add a client to the group
     *
     * @param Client $client
     * @param Group $group
     *
     * @return bool
     */
    public function addClient(Client $client, Group $group)
    {
        return $client->getId() === $group->adminClient->getId();
    }

    /**
     * Determine whether the user can remove a client from the group
     *
     * @param Client $client
     * @param Group $group
     *
     * @return bool
     */
    public function removeClient(Client $client, Group $group)
    {
        return $client->getId() === $group->adminClient->getId();
    }

}
