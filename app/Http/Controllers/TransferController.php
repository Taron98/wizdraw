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
use Wizdraw\Http\Requests\Transfer\TransferUsedAgencyRequest;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Models\TransferType;
use Wizdraw\Notifications\TransferMissingReceipt;
use Wizdraw\Notifications\TransferReceived;
use Wizdraw\Notifications\TransferSent;
use Wizdraw\Services\BankAccountService;
use Wizdraw\Services\ClientService;
use Wizdraw\Services\FeedbackService;
use Wizdraw\Services\FileService;
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

    /** @var FileService */
    private $fileService;
    /**
     * TransferController constructor.
     *
     * @param TransferService $transferService
     * @param ClientService $clientService
     * @param TransferReceiptService $transferReceiptService
     * @param BankAccountService $bankAccountService
     * @param FeedbackService $feedbackService
     * @param RateCacheService $rateCacheService
     * @param FileService $fileService
     */
    public function __construct(
        TransferService $transferService,
        ClientService $clientService,
        TransferReceiptService $transferReceiptService,
        BankAccountService $bankAccountService,
        FeedbackService $feedbackService,
        RateCacheService $rateCacheService,
        FileService $fileService
    ) {
        $this->transferService = $transferService;
        $this->clientService = $clientService;
        $this->transferReceiptService = $transferReceiptService;
        $this->bankAccountService = $bankAccountService;
        $this->feedbackService = $feedbackService;
        $this->rateCacheService = $rateCacheService;
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
            $resInputs = ['client' => $client, 'transfer' => $transfer];

            return $this->respondWithError('transfer_not_owned', Response::HTTP_FORBIDDEN, $resInputs);
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
        $paymentAgency = $request->input('paymentAgency');

        /** get NIS rates if necessary for request made from israel application */
        $this->rateCacheService->setKeyPrefix($request);
        /** @var RateCache $rate */
        $rate = $this->rateCacheService->find($receiverCountryId);

        if (!$client->canTransfer()) {
            return $this->respondWithError('could_not_transfer_unapproved_client', Response::HTTP_FORBIDDEN, $client);
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

                // TODO: REMOVE!!!!!!!
                // TODO: REMOVE!!!!!!!
                // TODO: REMOVE!!!!!!!
                // TODO: REMOVE!!!!!!!
                unset($deposit[ 'ifsc' ]);

                $bankBranchName = $request->input('deposit.bankBranchName');
                $bankBranchId = $request->input('deposit.bankBranchId');
                $bankAccount = $this->bankAccountService->createBankAccount($receiverClientId, $deposit,
                    $bankBranchName, $bankBranchId);

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
            $resInputs = ['receiver' => $receiver];

            return $this->respondWithError('could_not_update_receiver', Response::HTTP_BAD_REQUEST, $resInputs);
        }

        $transfer = $this->transferService->createTransfer($client, $rate, $bankAccount, $inputs);

        $qr = ['result' => false, 'qr' => ''];
        if($paymentAgency == 'circle-k'){
            $qr = $this->fileService->uploadQrCircleK($transfer->getTransactionNumber(), $transfer->getTotalAmountAttribute());
        }

        /** @var Transfer $transfer */
        $user->notify(
            (new TransferMissingReceipt($transfer))
                ->delay(Carbon::now()->addHour())
        );

        return $this->respond(array_merge($transfer->toArray(), [
            'transactions' => $client->transfers->count(), 'qrCode' => $qr
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
            $resInputs = ['transfer' => $transfer];

            return $this->respondWithError('transfer_has_receipt', Response::HTTP_BAD_REQUEST, $resInputs);
        }

        if ($client->cannot('addReceipt', $transfer)) {
            $resInputs = ['client' => $client, 'transfer' => $transfer];

            return $this->respondWithError('transfer_not_owned', Response::HTTP_FORBIDDE, $resInputs);
        }

        $inputs = $request->except('image');
        $receiptImage = $request->input('image');

        $receipt = $this->transferReceiptService->createReceipt($transfer->getTransactionNumber(),
            $receiptImage, $inputs);

        if (is_null($receipt)) {
            $resInputs = ['inputs' => $inputs];

            return $this->respondWithError('could_not_create_receipt', Response::HTTP_BAD_REQUEST, $resInputs);
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

        return $this->respond($client->transfers()->paginate());
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
        $agency = $request->input('agency');

        $branch = $this->transferService->nearby($latitude, $longitude, $agency);

        if (is_null($branch)) {
            return $this->respondWithError('no_branch_found', Response::HTTP_NOT_FOUND);
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
            $resInputs = ['client' => $client, 'transfer' => $transfer];

            return $this->respondWithError('transfer_already_feedbacked', Response::HTTP_BAD_REQUEST, $resInputs);
        }

        if (!$this->feedbackService->questionExists($request->input('feedbackQuestionId'))) {
            return $this->respondWithError('feedback_question_not_found', Response::HTTP_FORBIDDEN);
        }

        if ($client->cannot('feedback', $transfer)) {
            $resInputs = ['client' => $client, 'transfer' => $transfer];

            return $this->respondWithError('transfer_not_owned', Response::HTTP_FORBIDDEN, $resInputs);
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
        $isApproved = $request->user()->client->isApproved();
        return $this->respond(['canTransfer' => $canTransfer, 'isApproved' => $isApproved]);
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
            $resInputs = ['client' => $client, 'transfer' => $transfer];

            return $this->respondWithError('transfer_not_owned', Response::HTTP_FORBIDDEN, $resInputs);
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

    /**
     * @param NoParamRequest $request
     *
     * @return JsonResponse
     */
    public function last(NoParamRequest $request)
    {
        $client = $request->user()->client;
        $lastTransfer = $this->clientService->lastTransfer($client);

        if (is_null($lastTransfer)) {
            return $this->respondWithError('transfer_not_found', Response::HTTP_NOT_FOUND);
        }

        return $this->respond($lastTransfer);
    }

    /**
     * @param TransferUsedAgencyRequest $request
     *
     * @return JsonResponse
     */
    public function alreadyUsedPaymentAgency(TransferUsedAgencyRequest $request)
    {
        $paymentAgency = $request->input('paymentAgency');
        $client = $request->user()->client;
        $transfers = $client->transfers;
        foreach ($transfers as $transfer){
            $transferCheck = collect($transfer->toArray());
            $usedAgency = $transferCheck->contains($paymentAgency);
            if($usedAgency){
               return $this->respond([$paymentAgency => true]);
            }
        }
        return $this->respondWithError('agency_not_found', Response::HTTP_NOT_FOUND);
    }

    /**
     * @param $defaultCountryId
     *
     * @return string
     */
    public function limit($defaultCountryId)
    {
        $limit = $this->transferService->getLimit($defaultCountryId);
        if (is_null($limit) || sizeof($limit) === 0) {
            return $this->respondWithError('limit_not_found', Response::HTTP_NOT_FOUND);
        }
        return $this->respond(['limit' => $limit]);
    }
}
