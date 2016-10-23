<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Status;

/**
 * Class StatusRepository
 * @package Wizdraw\Repositories
 */
class StatusRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return Status::class;
    }

}