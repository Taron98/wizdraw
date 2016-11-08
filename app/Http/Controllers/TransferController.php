<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Wizdraw\Http\Requests\NoParamRequest;
use Wizdraw\Http\Requests\Transfer\TransferAddReceiptRequest;
use Wizdraw\Http\Requests\Transfer\TransferCreateRequest;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\TransferType;
use Wizdraw\Services\BankAccountService;
use Wizdraw\Services\ClientService;
use Wizdraw\Services\TransferReceiptService;
use Wizdraw\Services\TransferService;

/**
 * Class TransferController
 * @package Wizdraw\Http\Controllers
 */
class TransferController extends AbstractController
{
    /** @var  TransferService */
    private $transferService;

    /** @var  ClientService */
    private $clientService;

    /** @var  TransferReceiptService */
    private $transferReceiptService;

    /** @var BankAccountService */
    private $bankAccountService;

    /**
     * TransferController constructor.
     *
     * @param TransferService $transferService
     * @param ClientService $clientService
     * @param TransferReceiptService $transferReceiptService
     * @param BankAccountService $bankAccountService
     */
    public function __construct(
        TransferService $transferService,
        ClientService $clientService,
        TransferReceiptService $transferReceiptService,
        BankAccountService $bankAccountService
    ) {
        $this->transferService = $transferService;
        $this->clientService = $clientService;
        $this->transferReceiptService = $transferReceiptService;
        $this->bankAccountService = $bankAccountService;
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
     * @return JsonResponse
     */
    public function create(TransferCreateRequest $request) : JsonResponse
    {
        $client = $request->user()->client;
        $inputs = $request->inputs();

        $receiverClientId = $request->input('receiverClientId');
        $receiver = $request->input('receiver');

        $bankAccount = null;
        switch ($request->getTransferType()) {
            case TransferType::TYPE_PICKUP_CASH:
                $receiver = array_merge($receiver, $request->input('pickup'));

                break;

            case TransferType::TYPE_DEPOSIT:
            default:
                $deposit = $request->input('deposit');
                $bankBranchName = $request->input('deposit.bankBranchName');
                $bankAccount = $this->bankAccountService->createBankAccount($receiverClientId, $deposit,
                    $bankBranchName);

                if (is_null($bankAccount)) {
                    return $this->respondWithError('could_not_create_bank_account', Response::HTTP_BAD_REQUEST);
                }
        }

        /** @var Client $client */
        $receiverClient = $this->clientService->update($receiver, $receiverClientId);

        if (is_null($receiverClient)) {
            // todo: delete bank account?
            if (!is_null($bankAccount)) {
                $this->bankAccountService->delete($bankAccount->getId());
            }

            return $this->respondWithError('could_not_update_receiver', Response::HTTP_BAD_REQUEST);
        }

        $transfer = $this->transferService->createTransfer($client, $bankAccount, $inputs);

        return $this->respond(array_merge($transfer->toArray(), [
            'transactions' => $client->transfers->count(),
        ]));
    }

    /**
     * @param TransferAddReceiptRequest $request
     * @param Transfer $transfer
     *
     * @return JsonResponse
     */
    public function addReceipt(TransferAddReceiptRequest $request, Transfer $transfer) : JsonResponse
    {
        $client = $request->user()->client;

        if ($client->cannot('addReceipt', $transfer)) {
            return $this->respondWithError('transfer_not_owned', Response::HTTP_FORBIDDEN);
        }

        $inputs = $request->except('image');
        $receiptImage = $request->input('image');

        $receipt = $this->transferReceiptService->createReceipt($transfer->getTransactionNumber(),
            $receiptImage, $inputs);

        if (is_null($receipt)) {
            return $this->respondWithError('could_not_create_receipt', Response::HTTP_BAD_REQUEST);
        }

        $transfer = $this->transferService->addReceipt($transfer, $receipt);

        return $this->respond($transfer);
    }

    /**
     * Showing list of transfer route
     *
     * @param NoParamRequest $request
     *
     * @return JsonResponse
     */
    public function list(NoParamRequest $request) : JsonResponse
    {
        $client = $request->user()->client;

        return $this->respond($client->transfers);
    }

}
