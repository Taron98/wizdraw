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
     * @param null $bankBranchName
     * @param null $bankBranchId
     *
     * @return null|BankAccount
     */
    public function createBankAccount(int $clientId, array $attributes, $bankBranchName = null, $bankBranchId = null)
    {
        $bankBranch = null;
        $attributes['account_number'] = trim($attributes['account_number']);
        if (!is_null($bankBranchName)) {
            $bankBranch = $this->bankBranchService->findByName($bankBranchName);
            if (is_null($bankBranch)) {
                $bankBranch = $this->bankBranchService->createBankBranch($bankBranchName);
            }
        } else {
            if (!is_null($bankBranchId)) {
                $bankBranch = $this->bankBranchService->findByBranchId($bankBranchId);
                if (is_null($bankBranch)) {
                    $bankBranch = $this->bankBranchService->createBankBranchById($bankBranchId);
                }
            }
        }

        return $this->repository->createWithRelation($clientId, $attributes, $bankBranch);
    }

}