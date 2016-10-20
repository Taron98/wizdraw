<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\BankBranch;

/**
 * Class BankBranchRepository
 * @package Wizdraw\Repositories
 */
class BankBranchRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model()
    {
        return BankBranch::class;
    }

}