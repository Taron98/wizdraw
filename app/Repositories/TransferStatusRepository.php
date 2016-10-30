<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\TransferStatus;

/**
 * Class TransferStatusRepository
 * @package Wizdraw\Repositories
 */
class TransferStatusRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return TransferStatus::class;
    }

}