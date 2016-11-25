<?php

namespace Wizdraw\Services;

use Illuminate\Database\Eloquent\Collection;
use Wizdraw\Cache\Entities\RateCache;
use Wizdraw\Cache\Services\RateCacheService;
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

    /** @var RateCacheService */
    protected $rateCacheService;

    /**
     * TransferService constructor.
     *
     * @param TransferRepository $transferRepository
     * @param TransferReceiptService $transferReceiptService
     * @param TransferStatusService $transferStatusService
     * @param NatureService $natureService
     * @param RateCacheService $rateCacheService
     */
    public function __construct(
        TransferRepository $transferRepository,
        TransferReceiptService $transferReceiptService,
        TransferStatusService $transferStatusService,
        NatureService $natureService,
        RateCacheService $rateCacheService
    ) {
        $this->repository = $transferRepository;
        $this->transferReceiptService = $transferReceiptService;
        $this->transferStatusService = $transferStatusService;
        $this->natureService = $natureService;
        $this->rateCacheService = $rateCacheService;
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
     * @param BankAccount $bankAccount
     * @param array $attributes
     *
     * @return void|AbstractModel
     */
    public function createTransfer(Client $senderClient, BankAccount $bankAccount = null, array $attributes = [])
    {
        $initStatus = $this->transferStatusService->findByStatus(TransferStatus::STATUS_WAIT_FOR_PROCESS_COMPLIANCE);
        // todo: change when we'll add new natures
        $defaultNature = $this->natureService->findByNature(Nature::NATURE_SUPPORT_OR_GIFT);
        $defaultNatureIds = collect([$defaultNature])->pluck('id')->toArray();

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
        $statusWait = $this->transferStatusService->findByStatus(TransferStatus::STATUS_WAIT);

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
    public function validateMonthly(float $amount) : bool
    {
        $monthlyTotal = $amount + $this->repository->monthlyTransfer();

        return ($monthlyTotal <= self::MAX_MONTHLY_TRANSFER);
    }

    /**
     * @param int $receiverCountryId
     * @param float $amount
     * @param float $totalAmount
     * @param float $receiverAmount
     *
     * @return bool
     */
    public function validateTotals(
        int $receiverCountryId,
        float $amount,
        float $totalAmount,
        float $receiverAmount
    ) : bool
    {
        $commission = 22;
        $calcTotalAmount = $amount + $commission;

        /** @var RateCache $rate */
        $rate = $this->rateCacheService->find($receiverCountryId);
        $calcReceiverAmount = $amount * $rate->getRate();

        return (!bccomp($totalAmount, $calcTotalAmount, 3)) && !bccomp($receiverAmount, $calcReceiverAmount, 3);
    }

    /**
     * @param Transfer $transfer
     * @param int $statusId
     *
     * @return bool
     */
    public function changeStatus(Transfer $transfer, int $statusId) : bool
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