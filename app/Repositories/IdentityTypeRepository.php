<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\IdentityType;

/**
 * Class IdentityTypeRepository
 * @package Wizdraw\Repositories
 */
class IdentityTypeRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return IdentityType::class;
    }

}