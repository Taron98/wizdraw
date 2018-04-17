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
        $clientDefaultCountryId = Auth::user()->client->default_country_id;
        $transfers = Auth::user()->client->transfers->where('status_id','<>',9)->where('status_id','<>',1)->where('sender_country_id', '=', $clientDefaultCountryId);

        $total = $transfers
            ->where('created_at', '>=', Carbon::now()->subMonth(1))
            ->sum('amount');

        return $total;
    }

    public function findWithClient($transfers){
        $withClients = $this->model::whereIn('transaction_number', $transfers)->with('client')->get();
        return $withClients;
    }

}