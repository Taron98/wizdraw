<?php

namespace Wizdraw\Services;

use Illuminate\Database\Eloquent\Collection;
use Wizdraw\Cache\Entities\RateCache;
use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\BankAccount;
use Wizdraw\Models\Client;
use Wizdraw\Models\Nature;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\TransferReceipt;
use Wizdraw\Models\TransferStatus;
use Wizdraw\Repositories\TransferRepository;

/**
 * Class TransferService
 * @package Wizdraw\Services
 */
class TransferService extends AbstractService
{
    const MAX_MONTHLY_TRANSFER = 5000;

    /** @var TransferReceiptService */
    protected $transferReceiptService;

    /** @var TransferStatusService */
    protected $transferStatusService;

    /** @var  NatureService */
    protected $natureService;

    /**
     * TransferService constructor.
     *
     * @param TransferRepository $transferRepository
     * @param TransferReceiptService $transferReceiptService
     * @param TransferStatusService $transferStatusService
     * @param NatureService $natureService
     */
    public function __construct(
        TransferRepository $transferRepository,
        TransferReceiptService $transferReceiptService,
        TransferStatusService $transferStatusService,
        NatureService $natureService
    ) {
        $this->repository = $transferRepository;
        $this->transferReceiptService = $transferReceiptService;
        $this->transferStatusService = $transferStatusService;
        $this->natureService = $natureService;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->repository->with([
            'client',
            'receiverClient',
            'bankAccount',
            'natures',
            'status',
            'receipt',
        ])->find($id);
    }

    /**
     * @param Client $senderClient
     * @param RateCache $rate
     * @param BankAccount $bankAccount
     * @param array $attributes
     *
     * @return AbstractModel
     */
    public function createTransfer(
        Client $senderClient,
        RateCache $rate,
        BankAccount $bankAccount = null,
        array $attributes = []
    ) {
        $initStatus = $this->transferStatusService->findByStatus(TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_7_ELEVEN);
        // todo: change when we'll add new natures
        $defaultNature = $this->natureService->findByNature(Nature::NATURE_SUPPORT_OR_GIFT);
        $defaultNatureIds = collect([$defaultNature])->pluck('id')->toArray();

        $attributes[ 'rate' ] = $rate->getRate();

        $transfer = $this->repository->createWithRelation($senderClient, $bankAccount, $initStatus, $defaultNatureIds,
            $attributes);

        return $this->find($transfer->getId());
    }

    /**
     * @param Transfer $transfer
     * @param TransferReceipt $transferReceipt
     *
     * @return Transfer
     */
    public function addReceipt(Transfer $transfer, TransferReceipt $transferReceipt)
    {
        $statusWait = $this->transferStatusService->findByStatus(TransferStatus::STATUS_AWAITING_WITHDRAWAL);

        $transfer
            ->receipt()->associate($transferReceipt)
            ->status()->associate($statusWait)
            ->save();

        return $transfer;
    }

    /**
     * @param float $amount
     *
     * @return bool
     */
    public function validateMonthly(float $amount): bool
    {
        $monthlyTotal = $amount + $this->repository->monthlyTransfer();

        return ($monthlyTotal <= self::MAX_MONTHLY_TRANSFER);
    }

    /**
     * @param RateCache $rate
     * @param float $amount
     * @param float $commission
     * @param float $totalAmount
     * @param float $receiverAmount
     *
     * @return bool
     */
    public function validateTotals(
        RateCache $rate,
        float $amount,
        float $commission,
        float $totalAmount,
        float $receiverAmount
    ): bool {
        $calcTotalAmount = $amount + $commission;

        $calcReceiverAmount = $amount * $rate->getRate();

        return (!bccomp($totalAmount, $calcTotalAmount, 3)) && !bccomp($receiverAmount, $calcReceiverAmount, 3);
    }

    /**
     * @param Transfer $transfer
     * @param int $statusId
     *
     * @return bool
     */
    public function changeStatus(Transfer $transfer, int $statusId): bool
    {
        $status = $this->transferStatusService->find($statusId);
        $isUpdated = $transfer->status()->associate($status)->save();

        return $isUpdated;
    }

    /**
     * @return Collection
     */
    public function statuses()
    {
        return $this->transferStatusService->all();
    }

}