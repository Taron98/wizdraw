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
     * Determine whether the user can update the group
     *
     * @param Client $client
     * @param Group $group
     *
     * @return bool
     */
    public function update(Client $client, Group $group)
    {
        $groupClient = $group->adminClient()->first();

        return $client->getId() === $groupClient->getId();
    }

}
