<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Transfer;

/**
 * Class TransferRepository
 * @package Wizdraw\Repositories
 */
class TransferRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return Transfer::class;
    }

}