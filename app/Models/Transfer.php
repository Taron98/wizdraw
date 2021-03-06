<?php

namespace Wizdraw\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use stdClass;
use Wizdraw\Services\TransferService;

/**
 * Wizdraw\Models\Transfer
 *
 * @property integer $id
 * @property string $transactionNumber
 * @property integer $clientId
 * @property integer $receiverClientId
 * @property string $paymentAgency
 * @property integer $typeId
 * @property integer $bankAccountId
 * @property integer $receiverCountryId
 * @property integer $senderCountryId
 * @property string $cid
 * @property float $amount
 * @property float $commission
 * @property float $ilsBaseRate
 * @property float $ilsExchangeRate
 * @property float $rate
 * @property integer $statusId
 * @property integer $receiptId
 * @property float $latitude
 * @property float $longitude
 * @property string $note
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property string $supplier
 * @property-read mixed $qrCodeUrl
 * @property-read \Wizdraw\Models\Client $client
 * @property-read \Wizdraw\Models\Client $receiverClient
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\CampaignWithTransfer[] $campaignWithTransfer
 * @property-read \Wizdraw\Models\TransferType $type
 * @property-read \Wizdraw\Models\BankAccount $bankAccount
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Nature[] $natures
 * @property-read \Wizdraw\Models\TransferStatus $status
 * @property-read \Wizdraw\Models\TransferReceipt $receipt
 * @property-read mixed $totalAmount
 * @property-read mixed $receiverAmount
 * @property-read mixed $nearbyBranch
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereTransactionNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereReceiverClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer wherePaymentAgency($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereBankAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereReceiverCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereSenderCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereCid($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereCommission($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereIlsBaseRate($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereIlsExchangeRate($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereRate($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereStatusId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereReceiptId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereLatitude($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereLongitude($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereSupplier($value)
 * @mixin \Eloquent
 */
class Transfer extends AbstractModel implements AuthorizableContract
{
    use SoftDeletes, Authorizable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfers';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'total_amount',
        'receiver_amount',
        'nearby_branch',
        'qr_code_url',
    ];

    public function getQrCodeUrlAttribute()
    {
        $url = '';
        switch ($this->paymentAgency) {
            case 'wic-store':
                break;
            default:
                $url = $this->paymentAgency . '/' . $this->transactionNumber . '.jpg';
                break;
        }

        return $url;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_number',
        'client_id',
        'receiver_client_id',
        'payment_agency',
        'type_id',
        'bank_account_id',
        'receiver_country_id',
        'sender_country_id',
        'cid',
        'amount',
        'commission',
        'rate',
        'status_id',
        'receipt_id',
        'latitude',
        'longitude',
        'note',
        'status_id',
        'supplier',
        'ils_base_rate',
        'ils_exchange_rate'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'client_id',
        'receiver_client_id',
        'type_id',
        'receipt_id',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'real',
        'commission' => 'real',
        'rate' => 'real',
        'latitude' => 'real',
        'longitude' => 'real',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::creating(function ($model) {
            /** @var Transfer $model */
            // todo: change to the real thing
//            $randomNumber = (pow(10, 8) + time() % pow(10, 8));
//            $model->transactionNumber = 'WF9' . (string)$randomNumber;
            $model->transactionNumber = getWfId($model->supplier);
        });
    }
    public function getTransactionNumberAttribute()
    {
        if ($this->attributes['supplier'] === "Contact") {
            $newTransactionId = str_replace('WF', '97761', $this->attributes['transaction_number']);
            return $newTransactionId;
        }
        if ($this->attributes['supplier'] === 'Muthoot Pickup') {
            $prefix = strlen($this->attributes['transaction_number']) === 12 ? '84' : '840';
            $newTransactionId = str_replace('WF', $prefix, $this->attributes['transaction_number']);
            return $newTransactionId;
        }
         return $this->attributes['transaction_number'];
    }

    //<editor-fold desc="Relationships">
    // todo: receiverCountry()
    // todo: senderCountry()

    /**
     * The client that opened the transfer
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * The client that received the transfer
     */
    public function receiverClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'receiver_client_id');
    }

    /**
     * @return HasMany
     */
    public function campaignWithTransfer(): HasMany
    {
        return $this->hasMany(CampaignWithTransfer::class);
    }

    /**
     * Type of the transfer
     *
     * @return BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(TransferType::class);
    }

    /**
     * Bank account of the receiver client
     *
     * @return BelongsTo
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * Natures of the transfer
     *
     * @return BelongsToMany
     */
    public function natures(): BelongsToMany
    {
        return $this->belongsToMany(Nature::class, 'transfer_natures');
    }

    /**
     * Current status of the transfer
     *
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TransferStatus::class);
    }

    /**
     * Receipt of the transfer
     *
     * @return BelongsTo
     */
    public function receipt(): BelongsTo
    {
        return $this->belongsTo(TransferReceipt::class);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    /**
     * Total amount the receiver paid
     *
     * @return float
     */
    public function getTotalAmountAttribute()
    {
        return $this->attributes['amount'] + $this->attributes['commission'];
    }

    /**
     * Total amount that the receiver get paid
     *
     * @return float
     */
    public function getReceiverAmountAttribute()
    {
        return $this->attributes['amount'] * $this->attributes['rate'];
    }

    /**
     * Leaves only 6 decimal places
     *
     * @param $value
     */
    public function setLatitudeAttribute($value)
    {
        $this->attributes['latitude'] = bcdiv($value, 1, 6);
    }

    /**
     * Leaves only 6 decimal places
     *
     * @param $value
     */
    public function setLongitudeAttribute($value)
    {
        $this->attributes['longitude'] = bcdiv($value, 1, 6);
    }

    /**
     * Closest branch to the saved latitude and longitude
     *
     * @return null|stdClass
     */
    public function getNearbyBranchAttribute()
    {
        /** @var TransferService $transferService */
        $transferService = resolve(TransferService::class);

        return $transferService->nearby($this->latitude, $this->longitude, $this->paymentAgency);
    }
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getTransactionNumber()
    {
        return $this->transactionNumber;
    }

    /**
     * @param string $transactionNumber
     *
     * @return Transfer
     */
    public function setTransactionNumber($transactionNumber): Transfer
    {
        $this->transactionNumber = $transactionNumber;

        return $this;
    }

    /**
     * @return \Carbon\Carbon
     */
    public function getTransactionCreationDateAndTime()
    {
        return $this->createdAt->format('Y-m-d');
    }

    /**
     * @return int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     *
     * @return Transfer
     */
    public function setClientId($clientId): Transfer
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReceiverClientId()
    {
        return $this->receiverClientId;
    }

    /**
     * @param int $receiverClientId
     *
     * @return Transfer
     */
    public function setReceiverClientId($receiverClientId): Transfer
    {
        $this->receiverClientId = $receiverClientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentAgency()
    {
        return $this->paymentAgency;
    }

    /**
     * @param string $paymentAgency
     *
     * @return Transfer
     */
    public function setPaymentAgency($paymentAgency): Transfer
    {
        $this->paymentAgency = $paymentAgency;

        return $this;
    }

    /**
     * @return int
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param int $typeId
     *
     * @return Transfer
     */
    public function setTypeId($typeId): Transfer
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * @return int
     */
    public function getBankAccountId()
    {
        return $this->bankAccountId;
    }

    /**
     * @param int $bankAccountId
     *
     * @return Transfer
     */
    public function setBankAccountId($bankAccountId): Transfer
    {
        $this->bankAccountId = $bankAccountId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReceiverCountryId()
    {
        return $this->receiverCountryId;
    }

    /**
     * @param int $receiverCountryId
     *
     * @return Transfer
     */
    public function setReceiverCountryId($receiverCountryId): Transfer
    {
        $this->receiverCountryId = $receiverCountryId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSenderCountryId()
    {
        return $this->senderCountryId;
    }

    /**
     * @param int $senderCountryId
     *
     * @return Transfer
     */
    public function setSenderCountryId($senderCountryId): Transfer
    {
        $this->senderCountryId = $senderCountryId;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return Transfer
     */
    public function setAmount(float $amount): Transfer
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return float
     */
    public function getCommission(): float
    {
        return $this->commission;
    }

    /**
     * @param float $commission
     *
     * @return Transfer
     */
    public function setCommission($commission): Transfer
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     *
     * @return Transfer
     */
    public function setRate($rate): Transfer
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * @param int $statusId
     *
     * @return Transfer
     */
    public function setStatusId($statusId): Transfer
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReceiptId()
    {
        return $this->receiptId;
    }

    /**
     * @param int $receiptId
     *
     * @return Transfer
     */
    public function setReceiptId($receiptId): Transfer
    {
        $this->receiptId = $receiptId;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return Transfer
     */
    public function setLatitude($latitude): Transfer
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return Transfer
     */
    public function setLongitude($longitude): Transfer
    {
        $this->longitude = $longitude;

        return $this;
    }


    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     *
     * @return Transfer
     */
    public function setNote($note): Transfer
    {
        $this->note = $note;

        return $this;
    }
    //</editor-fold>

}
