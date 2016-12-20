<?php

namespace Wizdraw\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Wizdraw\Cache\Entities\RateCache;
use Wizdraw\Cache\Services\RateCacheService;
use Wizdraw\Http\Requests\NoParamRequest;
use Wizdraw\Http\Requests\Transfer\TransferAddReceiptRequest;
use Wizdraw\Http\Requests\Transfer\TransferCreateRequest;
use Wizdraw\Http\Requests\Transfer\TransferFeedbackRequest;
use Wizdraw\Http\Requests\Transfer\TransferNearbyRequest;
use Wizdraw\Http\Requests\Transfer\TransferStatusRequest;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\TransferType;
use Wizdraw\Notifications\TransferMissingReceipt;
use Wizdraw\Notifications\TransferReceived;
use Wizdraw\Notifications\TransferSent;
use Wizdraw\Services\BankAccountService;
use Wizdraw\Services\ClientService;
use Wizdraw\Services\FeedbackService;
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

    /** @var FeedbackService */
    private $feedbackService;

    /** @var RateCacheService */
    protected $rateCacheService;

    /**
     * TransferController constructor.
     *
     * @param TransferService $transferService
     * @param ClientService $clientService
     * @param TransferReceiptService $transferReceiptService
     * @param BankAccountService $bankAccountService
     * @param FeedbackService $feedbackService
     * @param RateCacheService $rateCacheService
     */
    public function __construct(
        TransferService $transferService,
        ClientService $clientService,
        TransferReceiptService $transferReceiptService,
        BankAccountService $bankAccountService,
        FeedbackService $feedbackService,
        RateCacheService $rateCacheService
    ) {
        $this->transferService = $transferService;
        $this->clientService = $clientService;
        $this->transferReceiptService = $transferReceiptService;
        $this->bankAccountService = $bankAccountService;
        $this->feedbackService = $feedbackService;
        $this->rateCacheService = $rateCacheService;
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
    public function create(TransferCreateRequest $request): JsonResponse
    {
        $user = $request->user();
        $client = $user->client;
        $inputs = $request->inputs();

        $receiverClientId = $request->input('receiverClientId');
        $receiver = $request->input('receiver');
        $amount = $request->input('amount');
        $totalAmount = $request->input('totalAmount');
        $commission = $request->input('commission');
        $receiverAmount = $request->input('receiverAmount');
        $receiverCountryId = $request->input('receiverCountryId');
        /** @var RateCache $rate */
        $rate = $this->rateCacheService->find($receiverCountryId);

        if (!$client->canTransfer()) {
            return $this->respondWithError('could_not_transfer_unapproved_client', Response::HTTP_FORBIDDEN);
        }

        if (!$this->transferService->validateMonthly($amount)) {
            return $this->respondWithError('max_monthly_transfer_reached', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$this->transferService->validateTotals($rate, $amount, $commission, $totalAmount, $receiverAmount)) {
            return $this->respondWithError('totals_are_invalid', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

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
            if (!is_null($bankAccount)) {
                $this->bankAccountService->delete($bankAccount->getId());
            }

            return $this->respondWithError('could_not_update_receiver', Response::HTTP_BAD_REQUEST);
        }

        $transfer = $this->transferService->createTransfer($client, $rate, $bankAccount, $inputs);

        $user->notify(
            (new TransferMissingReceipt($transfer))
                ->delay(Carbon::now()->addHour())
        );

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
    public function addReceipt(TransferAddReceiptRequest $request, Transfer $transfer): JsonResponse
    {
        $client = $request->user()->client;

        if (!is_null($transfer->receipt)) {
            return $this->respondWithError('transfer_has_receipt', Response::HTTP_BAD_REQUEST);
        }

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

        $transfer->client->notify(new TransferSent($transfer));
        $transfer->receiverClient->notify(new TransferReceived($transfer));

        return $this->respond($transfer);
    }

    /**
     * Showing list of transfer route
     *
     * @param NoParamRequest $request
     *
     * @return JsonResponse
     */
    public function list(NoParamRequest $request): JsonResponse
    {
        $client = $request->user()->client;
        $transferByLatest = $client->transfers()->latest();

        return $this->respond($transferByLatest->paginate());
    }

    /**
     * Getting nearby 7eleven branch by location
     *
     * @param TransferNearbyRequest $request
     *
     * @return JsonResponse
     */
    public function nearby(TransferNearbyRequest $request): JsonResponse
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $branch = $this->transferService->nearby($latitude, $longitude);

        if (is_null($branch)) {
            return $this->respondWithError('no_branch_found');
        }

        return $this->respond($branch);
    }

    /**
     * @param TransferFeedbackRequest $request
     * @param Transfer $transfer
     *
     * @return JsonResponse
     */
    public function feedback(TransferFeedbackRequest $request, Transfer $transfer)
    {
        $client = $request->user()->client;

        if ($this->feedbackService->alreadyFeedbacked($client, $transfer)) {
            return $this->respondWithError('transfer_already_feedbacked', Response::HTTP_BAD_REQUEST);
        }

        if (!$this->feedbackService->questionExists($request->input('feedbackQuestionId'))) {
            return $this->respondWithError('feedback_question_not_found', Response::HTTP_FORBIDDEN);
        }

        if ($client->cannot('feedback', $transfer)) {
            return $this->respondWithError('transfer_not_owned', Response::HTTP_FORBIDDEN);
        }

        $inputs = $request->inputs();

        return $this->feedbackService->createFeedback($client, $transfer, $inputs);
    }

    /**
     * @param NoParamRequest $request
     *
     * @return JsonResponse
     */
    public function able(NoParamRequest $request)
    {
        $canTransfer = $request->user()->client->canTransfer();

        return $this->respond(compact('canTransfer'));
    }

    /**
     * @param TransferStatusRequest $request
     * @param Transfer $transfer
     *
     * @return JsonResponse
     */
    public function status(TransferStatusRequest $request, Transfer $transfer)
    {
        $client = $request->user()->client;

        if ($client->cannot('updateStatus', $transfer)) {
            return $this->respondWithError('transfer_not_owned', Response::HTTP_FORBIDDEN);
        }

        if (!$this->transferService->changeStatus($transfer, $request->input('transferStatusId'))) {
            return $this->respondWithError('could_not_update_status', Response::HTTP_FORBIDDEN);
        }

        $canTransfer = $request->user()->client->canTransfer();

        return $this->respond(compact('canTransfer'));
    }

    /**
     * @return JsonResponse
     */
    public function statuses()
    {
        $statuses = $this->transferService->statuses();

        return $this->respond($statuses);
    }

}
