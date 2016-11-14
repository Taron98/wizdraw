<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\TransferStatus;

/**
 * Class TransferTypeRepository
 * @package Wizdraw\Repositories
 */
class TransferTypeRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return TransferStatus::class;
    }

}