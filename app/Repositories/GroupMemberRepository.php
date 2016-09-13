<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\GroupMember;

/**
 * Class GroupMemberRepository
 * @package Wizdraw\Repositories
 */
class GroupMemberRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return GroupMember::class;
    }

}