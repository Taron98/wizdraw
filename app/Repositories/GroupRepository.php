<?php

namespace Wizdraw\Repositories;

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

}