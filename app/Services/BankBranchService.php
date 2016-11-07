<?php

namespace Wizdraw\Services;

use Wizdraw\Models\BankBranch;
use Wizdraw\Repositories\BankBranchRepository;

/**
 * Class BankBranchService
 * @package Wizdraw\Services
 */
class BankBranchService extends AbstractService
{

    /**
     * BankBranchService constructor.
     *
     * @param BankBranchRepository $bankBranchRepository
     */
    public function __construct(BankBranchRepository $bankBranchRepository)
    {
        $this->repository = $bankBranchRepository;
    }

    /**
     * @param string $name
     *
     * @return BankBranch
     */
    public function createBankAccount(string $name) : BankBranch
    {
        return $this->repository->create(compact('name'));
    }

}