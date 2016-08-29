<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\User;

/**
 * Class UserRepository
 * @package Wizdraw\Repositories
 */
class UserRepository extends BaseRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return User::class;
    }

}