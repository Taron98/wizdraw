<?php

namespace Wizdraw\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Wizdraw\Models\BankAccount;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\TransferStatus;

/**
 * Class TransferRepository
 * @package Wizdraw\Repositories
 */
class TransferRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return Transfer::class;
    }

    /**
     * Create a transfer with his relationships
     *
     * @param Client $senderClient
     *
     * @param BankAccount $bankAccount
     * @param TransferStatus $status
     * @param array $natures
     * @param array $attributes
     *
     * @return null|Transfer
     */
    public function createWithRelation(
        Client $senderClient,
        BankAccount $bankAccount = null,
        TransferStatus $status,
        array $natures,
        array $attributes
    ) {
        /** @var Transfer $newTransfer */
        $newTransfer = $this->makeModel()->fill($attributes);

        $newTransfer->client()->associate($senderClient);
        $newTransfer->bankAccount()->associate($bankAccount);
        $newTransfer->status()->associate($status);

        if (!$newTransfer->save()) {
            return null;
        }

        $newTransfer->natures()->sync($natures);

        return $newTransfer;
    }

    /**
     * @return float
     */
    public function monthlyTransfer() : float
    {
        $transfers = Auth::user()->client->transfers;

        $total = $transfers
            ->where('created_at', '>=', Carbon::now()->subMonth(1))
            ->sum('amount');

        return $total;
    }

}