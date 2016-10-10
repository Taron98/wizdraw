<?php

namespace Wizdraw\Repositories;

use Illuminate\Support\Collection;
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
    public function model()
    {
        return Group::class;
    }

    /**
     * @param Client $client
     *
     * @return Collection
     */
    public function findByAdminClient(Client $client) : Collection
    {
        return $this->findByField('admin_client_id', $client->getId());
    }

    /**
     * Create a group with his relationships
     *
     * @param Client $adminClient
     *
     * @param array $attributes
     * @param array $groupClients
     *
     * @return mixed
     */
    public function createWithRelation(Client $adminClient, array $attributes, array $groupClients = [])
    {
        $newGroup = $this->create($attributes);

        // Set the admin client id of the group
        $newGroup->adminClient()
            ->associate($adminClient)->save();

        // Attach the members of the group
        $newGroup->memberClients()->attach($groupClients);

        return (is_null($newGroup)) ?: $newGroup;
    }

    /**
     * Update a group with his relationships
     *
     * @param int $id
     * @param array $attributes
     * @param array $groupClients
     *
     * @return mixed
     */
    public function updateWithRelation(int $id, array $attributes, array $groupClients = [])
    {
        $newGroup = $this->update($attributes, $id);

        // Attach the members of the group
        $newGroup->memberClients()->sync($groupClients);

        return (is_null($newGroup)) ?: $newGroup;
    }

}