<?php

namespace Wizdraw\Services;

use Carbon\Carbon;
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
use Wizdraw\Models\User;
use Wizdraw\Models\Campaign;
use Wizdraw\Repositories\TransferRepository;
use Wizdraw\Notifications\TransferAborted;
use GuzzleHttp\Client as GuzzleClient;



/**
 * Class TransferService
 * @package Wizdraw\Services
 */
class TransferService extends AbstractService
{
    const DEFAULT_MAX_MONTHLY_TRANSFER = 8000;
    const DEFAULT_MAX_YEARLY_TRANSFER = 210000;
    //@TODO - for the future, if we add more sending countries, we need to make this limits come from the db
    const MONTHLY_LIMITS_ARRAY = array(90 => 4500, 13 => 35000, 119 => 60000);
    const YEARLY_LIMITS_ARRAY = array(13 => 210000);

    const HONG_KONG_SENDER = 90;

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
    )
    {
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
    )
    {
        if ($attributes['payment_agency'] == 'circle-k') {
            $transferStatus = TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_CIRCLE_K;
        } elseif ($attributes['payment_agency'] == '7-eleven') {
            $transferStatus = TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_7_ELEVEN;
        } elseif ($attributes['payment_agency'] == 'wic-store' && isset($attributes['c_id']) && isset($attributes['sms_code'])) {
            $transferStatus = TransferStatus::STATUS_WAIT;
            unset($attributes['c_id']);
            unset($attributes['sms_code']);
        } elseif ($attributes['payment_agency'] == 'pay-to-agent') {
            $transferStatus = TransferStatus::STATUS_PENDING_FOR_PAYMENT_AT_PAY_TO_AGENT;
        } else {
            $transferStatus = TransferStatus::STATUS_PENDING;
        }

        $initStatus = $this->transferStatusService->findByStatus($transferStatus);
        // todo: change when we'll add new natures
        $defaultNature = $this->natureService->findByNature(Nature::NATURE_SUPPORT_OR_GIFT);
        $defaultNatureIds = collect([$defaultNature])->pluck('id')->toArray();

        $attributes['rate'] = $rate->getRate();

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
     * @param Client $senderClient
     * @return bool
     */
    public function validateMonthly(float $amount, Client $senderClient): bool
    {
        $hongKongSender = $senderClient->default_country_id === self::HONG_KONG_SENDER;
        $monthlyTotal = $amount + $this->repository->monthlyTransfer($hongKongSender);
        $senderClientCountry = $senderClient->default_country_id;
        $monthlyLimit = array_key_exists($senderClientCountry, self::MONTHLY_LIMITS_ARRAY) ? self::MONTHLY_LIMITS_ARRAY[$senderClientCountry] : self::DEFAULT_MAX_MONTHLY_TRANSFER;

        return ($monthlyTotal <= $monthlyLimit);
    }

    /**
     * @param float $amount
     * @param Client $senderClient
     * @return bool
     */
    public function validateYearly(float $amount, Client $senderClient): bool
    {
        $yearlyTotal = $amount + $this->repository->yearlyTransfer();
        $senderClientCountry = $senderClient->default_country_id;
        $yearlyLimit = array_key_exists($senderClientCountry, self::YEARLY_LIMITS_ARRAY) ? self::YEARLY_LIMITS_ARRAY[$senderClientCountry] : self::DEFAULT_MAX_YEARLY_TRANSFER;

        return array_key_exists($senderClientCountry, self::YEARLY_LIMITS_ARRAY) ? ($yearlyTotal <= $yearlyLimit) : true;
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
    ): bool
    {
        $calcTotalAmount = $amount + $commission;

        $calcReceiverAmount = $amount * $rate->getRate();

        //todo: need to figure out what to do if the client not send round amount, temporary fix
        return (!bccomp($totalAmount, $calcTotalAmount, 3)) /*&& !bccomp($receiverAmount, $calcReceiverAmount, 3)*/
            ;
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
        if ($agency == self::AGENCY_7_ELEVEN) {
            // todo: this solution is hardcoded for the 1st version
            $branchesJson = json_decode(file_get_contents(database_path('cache/branches.json')), true);
        } elseif ($agency == self::AGENCY_CIRCLE_K) {
            $branchesJson = json_decode(file_get_contents(database_path('cache/branchesCircleK.json')), true);
        } elseif ($agency == self::AGENCY_WIC_STORE) {
            $branchesJson = json_decode(file_get_contents(database_path('cache/branchesWicStore.json')), true);
        } else {
            $branchesJson = json_decode(file_get_contents(database_path('cache/branchesPayToAgent.json')), true);
        }
        $branches = collect();
        foreach ($branchesJson as $branch) {
            $distance = $this->distance(
                (float)$latitude,
                (float)$longitude,
                (float)$branch['latitude'],
                (float)$branch['longitude']
            );

            if ($distance <= 10) {
                $branch['distance'] = (float)$distance;

                $branches->put($branch['id'], $branch);
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
        return $redis->lrange(redis_key('origin', $defaultCountryId, 'amountLimits'), 0, -1);

    }

    public function clientNotifyAbortedStatus($transfers)
    {
        $abortedTransfers = $this->repository->findWithClient($transfers);
        foreach ($abortedTransfers as $transfer) {
            $client = $transfer->client;
            /** @var  User */
            $user = $client->user;
            $user->notify((new TransferAborted($transfer)));
        }

        return true;
    }

    /**
     * @desc check if the user is entitled for hk_first_five_transfers campaign
     * @param Client $client
     * @param $campaign
     * @return bool
     */
    public function isEntitledForHkFirstFiveTransfersCampaign(Client $client, $campaign){
        if(!$this->isEntitledForCampaign($campaign)){
            return false;
        }
        //At first, the condition for being entitled to the campaign was for the first 5 transfers only (as the function name..)
//        $clientLastTransfersBetweenDates = $this->repository
//            ->getClientLastTransfersBetweenDates($client->transfers, 90, $campaign[0]->start_date, $campaign[0]->end_date);

//        return ((sizeof($clientLastTransfersBetweenDates)) < 5 && ($client->defaultCountryId === 90)) ? true : false;
        return $client->defaultCountryId === 90 ? true : false;
    }

    /**
     * @desc check if the user is entitled for a campaign by the campaign start and end dates and if it's active
     * @param $campaign
     * @return bool
     */
    private function isEntitledForCampaign($campaign){
        if((!$campaign[0]->active) || (Carbon::now() < $campaign[0]->start_date) || (Carbon::now() > $campaign[0]->end_date)){
            return false;
        }
        return true;
    }

    /**
     * @desc check if the user is in terrorists list
     * @param Client $sender
     * @param $receiver
     * @return bool
     */
    public function isNotBlackListed(Client $sender, $receiver){
//        if($sender->defaultCountryId !== 13){
//            return true;
//        }

        $client = new GuzzleClient();
        $url = "http://34.235.30.82/api/v1/black-list";

        $fullName = [
            'firstName' => $sender->first_name,
            'lastName' => $sender->last_name,
            'middleName' => $sender->middle_name,
        ];
        $receiverName = [
            'firstName' => $receiver['first_name'],
            'lastName' => $receiver['last_name'],
            'middleName' => $receiver['middle_name'],
        ];

        $request = $client->post($url,  ['multipart'=> [ $fullName ] ]);
        $response = $request->send();
dd($response);
        $receiverRequest = $client->post($url,  ['body'=>$receiverName]);
        $receiverResponse = $receiverRequest->send();

        dump($response, $receiverResponse);

        if($response['error'] || $receiverResponse['error']){
            return false;
        }
        return true;

    }
}