<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\IdentityType;

/**
 * Class IdentityTypeRepository
 * @package Wizdraw\Repositories
 */
class IdentityTypeRepository extends BaseRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return IdentityType::class;
    }

}