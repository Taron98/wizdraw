<?php

namespace Wizdraw\Services;

use Wizdraw\Models\BankAccount;
use Wizdraw\Repositories\BankAccountRepository;

/**
 * Class BankAccountService
 * @package Wizdraw\Services
 */
class BankAccountService extends AbstractService
{

    /** @var  BankBranchService */
    protected $bankBranchService;

    /**
     * BankAccountService constructor.
     *
     * @param BankAccountRepository $bankAccountRepository
     * @param BankBranchService $bankBranchService
     */
    public function __construct(BankAccountRepository $bankAccountRepository, BankBranchService $bankBranchService)
    {
        $this->repository = $bankAccountRepository;
        $this->bankBranchService = $bankBranchService;
    }

    /**
     * @param int $clientId
     * @param array $attributes
     * @param string $bankBranchName
     *
     * @return BankAccount|null
     */
    public function createBankAccount(int $clientId, array $attributes, $bankBranchName = null)
    {
        $bankBranch = null;
        if (!is_null($bankBranchName)) {
            $bankBranch = $this->bankBranchService->createBankBranch($bankBranchName);
        }

        return $this->repository->createWithRelation($clientId, $attributes, $bankBranch);
    }

}