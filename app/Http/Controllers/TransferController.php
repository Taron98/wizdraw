<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\Response;
use Wizdraw\Http\Requests\NoParamRequest;
use Wizdraw\Http\Requests\Transfer\TransferCreateRequest;
use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\Transfer;
use Wizdraw\Services\FileService;
use Wizdraw\Services\TransferService;

/**
 * Class TransferController
 * @package Wizdraw\Http\Controllers
 */
class TransferController extends AbstractController
{
    /** @var  TransferService */
    private $transferService;

    /** @var  FileService */
    private $fileService;

    /**
     * TransferController constructor.
     *
     * @param TransferService $transferService
     * @param FileService $fileService
     */
    public function __construct(TransferService $transferService, FileService $fileService)
    {
        $this->transferService = $transferService;
        $this->fileService = $fileService;
    }

    /**
     * Showing a transfer route
     *
     * @param NoParamRequest $request
     * @param Transfer $transfer
     *
     * @return mixed
     */
    public function show(NoParamRequest $request, Transfer $transfer)
    {
        $client = $request->user()->client;

        if ($client->cannot('show', $transfer)) {
            return $this->respondWithError('transfer_not_owned', Response::HTTP_FORBIDDEN);
        }

        return $this->transferService->find($transfer->getId());
    }

    /**
     * Creating a transfer route
     *
     * @param TransferCreateRequest $request
     *
     * @return AbstractModel
     */
    public function create(TransferCreateRequest $request)
    {
        $client = $request->user()->client;
        $inputs = $request->inputs();

        // todo: refactor
        // todo: move after the transfer and save with id of the transfer created
        $receiptImage = $request->input('receipt.image');
        if (!empty($receiptImage)) {
            $uploadStatus = $this->fileService->upload(FileService::TYPE_RECEIPT, $client->getId(), $receiptImage);

            if (!$uploadStatus) {
                return $this->respondWithError('Problem uploading receipt image', Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->transferService->createTransfer($client, $inputs);
    }

}
