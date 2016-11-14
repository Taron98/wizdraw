<?php

namespace Wizdraw\Services;

use Wizdraw\Models\TransferReceipt;
use Wizdraw\Repositories\TransferReceiptRepository;

/**
 * Class TransferReceiptService
 * @package Wizdraw\Services
 */
class TransferReceiptService extends AbstractService
{

    /** @var FileService */
    protected $fileService;

    /**
     * TransferReceiptService constructor.
     *
     * @param TransferReceiptRepository $transferReceiptRepository
     * @param FileService $fileService
     */
    public function __construct(TransferReceiptRepository $transferReceiptRepository, FileService $fileService)
    {
        $this->repository = $transferReceiptRepository;
        $this->fileService = $fileService;
    }

    /**
     * @param string $transactionNumber
     * @param string $receiptImage
     * @param array $attributes
     *
     * @return null|TransferReceipt
     */
    public function createReceipt(string $transactionNumber, string $receiptImage, array $attributes)
    {
        $uploadStatus = $this->fileService->uploadReceipt($transactionNumber, $receiptImage);

        if (!$uploadStatus) {
            return null;
        }

        return $this->repository->create($attributes);
    }

}