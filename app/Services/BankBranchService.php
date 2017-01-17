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
    public function createBankBranch(string $name): BankBranch
    {
        return $this->repository->create(compact('name'));
    }

    /**
     * @param string $bank_branch_id
     *
     * @return BankBranch
     */
    public function createBankBranchById(string $bank_branch_id): BankBranch
    {
        return $this->repository->create(compact('bank_branch_id'));
    }

    /**
     * @param string $name
     *
     * @return BankBranch
     */
    public function findByName(string $name)
    {
        return $this->repository->findByField('name', $name)->first();
    }

    /**
     * @param string $id
     *
     * @return BankBranch
     */
    public function findByBranchId(string $id)
    {
        return $this->repository->findByField('bank_branch_id', $id)->first();
    }

}