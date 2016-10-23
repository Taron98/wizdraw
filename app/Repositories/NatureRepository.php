<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Nature;

/**
 * Class NatureRepository
 * @package Wizdraw\Repositories
 */
class NatureRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return Nature::class;
    }

}