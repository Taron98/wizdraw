<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\TransferReceipt;

/**
 * Class TransferReceiptRepository
 * @package Wizdraw\Repositories
 */
class TransferReceiptRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return TransferReceipt::class;
    }

}