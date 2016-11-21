<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Client;
use Wizdraw\Models\Group;

/**
 * Class GroupRepository
 * @package Wizdraw\Repositories
 */
class GroupRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return Group::class;
    }

    /**
     * Create a group with his relationships
     *
     * @param Client $adminClient
     *
     * @param array $attributes
     * @param array $groupClientIds
     *
     * @return mixed
     */
    public function createWithRelation(Client $adminClient, array $attributes, array $groupClientIds = [])
    {
        $newGroup = $this->create($attributes);

        // Set the admin client id of the group
        $newGroup->adminClient()
            ->associate($adminClient)->save();

        // Attach the members of the group
        $newGroup->memberClients()->attach($groupClientIds);

        return (is_null($newGroup)) ?: $newGroup->load('memberClients');
    }

    /**
     * @param Group $group
     * @param array $groupClientIds
     *
     * @return mixed
     */
    public function addClient(Group $group, array $groupClientIds = [])
    {
        // Add missing clients to group
        $group->memberClients()->syncWithoutDetaching($groupClientIds);

        return $group->load('memberClients');
    }

    /**
     * @param Group $group
     * @param array $groupClientIds
     *
     * @return mixed
     */
    public function removeClient(Group $group, array $groupClientIds = [])
    {
        // Remove clients from the group
        $group->memberClients()->detach($groupClientIds);

        return $group->load('memberClients');
    }

}