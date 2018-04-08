<?php

namespace Wizdraw\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redis;
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
    const MAX_MONTHLY_TRANSFER = 8000;
    const AGENCY_7_ELEVEN = '7-eleven';
    const AGENCY_CIRCLE_K = 'circle-k';
    const AGENCY_WIC_STORE = 'wic-store';

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
        if($attributes['payment_agency'] == 'circle-k'){
            $transferStatus = TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_CIRCLE_K;
        }elseif($attributes['payment_agency'] == '7-eleven'){
            $transferStatus = TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_7_ELEVEN;
        }elseif($attributes['payment_agency'] == 'pay-to-agent'){
            $transferStatus = TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_PAY_TO_AGENT;
        } else{
            $transferStatus = TransferStatus::STATUS_PENDING;
        }

        $initStatus = $this->transferStatusService->findByStatus($transferStatus);
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
        $statusWait = $this->transferStatusService->findByStatus(TransferStatus::STATUS_PENDING);

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

        //todo: need to figure out what to do if the client not send round amount, temporary fix
        return (!bccomp($totalAmount, $calcTotalAmount, 3)) /*&& !bccomp($receiverAmount, $calcReceiverAmount, 3)*/;
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

    /**
     * @param float $latitude
     * @param float $longitude
     * @param $agency
     *
     * @return mixed
     */
    public function nearby(float $latitude, float $longitude, $agency)
    {
        if($agency == self::AGENCY_7_ELEVEN){
        // todo: this solution is hardcoded for the 1st version
        $branchesJson = json_decode(file_get_contents(database_path('cache/branches.json')), true);
        }elseif ($agency == self::AGENCY_CIRCLE_K){
            $branchesJson = json_decode(file_get_contents(database_path('cache/branchesCircleK.json')), true);
        }elseif($agency == self::AGENCY_WIC_STORE){
            $branchesJson = json_decode(file_get_contents(database_path('cache/branchesWicStore.json')), true);
        }else{
            $branchesJson = json_decode(file_get_contents(database_path('cache/branchesPayToAgent.json')), true);
        }
        $branches = collect();
        foreach ($branchesJson as $branch) {
            $distance = $this->distance(
                (float)$latitude,
                (float)$longitude,
                (float)$branch[ 'latitude' ],
                (float)$branch[ 'longitude' ]
            );

            if ($distance <= 10) {
                $branch[ 'distance' ] = (float)$distance;

                $branches->put($branch[ 'id' ], $branch);
            }
        }

        return $branches->sortBy('distance')->first();
    }

    /**
     * todo: refactor
     *
     * @param $lat1
     * @param $lon1
     * @param $lat2
     * @param $lon2
     *
     * @return float
     */
    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return ($miles * 1.609344);
    }

    /**
     * @param $defaultCountryId
     *
     * @return string
     */
    public function getLimit($defaultCountryId)
    {
        $redis = Redis::connection();
        return $redis->lrange(redis_key('origin',$defaultCountryId,'amountLimits'), 0, -1);

    }

    public function clientNotifyAbortedStatus($transfers)
    {
        $res = $this->repository->findWithClient($transfers);
        foreach ($res as $transfer){
            //@todo - set new notification for each client
            $transfer->client->notify();
        }
        $e = 1;

    }

}