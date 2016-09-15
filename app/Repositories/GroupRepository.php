<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\AbstractModel;
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
     * @param array         $data
     * @param AbstractModel $client
     *
     * @return mixed
     */
    public function createWithRelation(array $data, AbstractModel $client)
    {
        $data = array_key_snake_case($data);

        $newModel = $this->model
            ->newInstance($data)
            ->client()->associate($client);

        $success = $newModel->save();

        return (!$success) ?: $newModel;
    }

}