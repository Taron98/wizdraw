<?php

namespace Wizdraw\Repositories;

use Illuminate\Support\Collection;
use Wizdraw\Models\BankAccount;
use Wizdraw\Models\BankBranch;

/**
 * Class BankAccountRepository
 * @package Wizdraw\Repositories
 */
class BankAccountRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return BankAccount::class;
    }

    /**
     * Create a transfer with his relationships
     *
     * @param int $clientId
     * @param array $attributes
     * @param BankBranch $bankBranch
     *
     * @return BankAccount|null
     */
    public function createWithRelation(int $clientId, array $attributes = [], BankBranch $bankBranch = null)
    {
        $attributes = array_merge($attributes, [
            'client_id' => $clientId,
        ]);

        // todo: baaa, find other way
        if (array_key_exists('bank_branch_name', $attributes)) {
            unset($attributes['bank_branch_name']);
        }

        // Only if we haven't got a branch, we need to check if the account already exists
        if (!is_null($bankBranch)) {
            $attributes = array_merge($attributes, [
                'bank_branch_id' => $bankBranch->getId(),
            ]);
        } else {
            /** @var Collection $bankAccount */
            $bankAccount = $this->findWhere($attributes);

            if ($bankAccount->count()) {
                return $bankAccount->first();
            }
        }

        return $this->create($attributes);
    }

}