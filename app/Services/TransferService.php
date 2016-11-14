<?php

namespace Wizdraw\Services;

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

}