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
     * @param bool $isHK
     * @return float
     */
    public function monthlyTransfer($isHK = false) : float
    {
        $filterDate = Carbon::now()->subMonth(1);
        if ($isHK) {
            $filterDate = Carbon::now()->startOfMonth();
        }
        $transfers = $this->getClientValidTransfers();
        $total = $transfers
            ->where('created_at', '>=', $filterDate)
            ->sum('amount');

        return $total;
    }

    /**
     * @return float
     */
    public function yearlyTransfer() : float
    {
        $transfers = $this->getClientValidTransfers();
        $total = $transfers
            ->where('created_at', '>=', Carbon::now()->subYear(1))
            ->sum('amount');

        return $total;
    }

    /**
     * @return static
     */
    private function getClientValidTransfers(){
        $clientDefaultCountryId = Auth::user()->client->default_country_id;

        return Auth::user()->client->transfers
            ->where('status_id','<>',9)
            ->where('status_id','<>',1)
            ->where('sender_country_id', '=', $clientDefaultCountryId);
    }

    /**
     * @param $transfers
     * @return mixed
     */
    public function findWithClient($transfers){
        $withClients = $this->model::whereIn('transaction_number', $transfers)->with('client')->get();
        return $withClients;
    }

    /**
     * @param $transfers
     * @param $originCountry
     * @param $from
     * @param $to
     * @return mixed
     */
    public function getClientLastTransfersBetweenDates($transfers, $originCountry, $from, $to){
        return $transfers
            ->where('sender_country_id', $originCountry)
            ->where('created_at','>=', $from)
            ->where('created_at','<=', $to)
            ->where('status_id','<>', 1)
            ->where('status_id','<>', 9);
    }

}