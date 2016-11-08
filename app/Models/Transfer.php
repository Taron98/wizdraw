<?php

namespace Wizdraw\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Facades\Auth;

/**
 * Wizdraw\Models\Transfer
 *
 * @property integer $id
 * @property string $transactionNumber
 * @property integer $clientId
 * @property integer $receiverClientId
 * @property integer $typeId
 * @property integer $bankAccountId
 * @property integer $receiverCountryId
 * @property integer $senderCountryId
 * @property float $amount
 * @property float $commission
 * @property integer $statusId
 * @property integer $receiptId
 * @property string $note
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Wizdraw\Models\Client $client
 * @property-read \Wizdraw\Models\Client $receiverClient
 * @property-read \Wizdraw\Models\TransferType $type
 * @property-read \Wizdraw\Models\BankAccount $bankAccount
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Nature[] $natures
 * @property-read \Wizdraw\Models\TransferStatus $status
 * @property-read \Wizdraw\Models\TransferReceipt $receipt
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereTransactionNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereReceiverClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereBankAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereReceiverCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereSenderCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereCommission($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereStatusId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereReceiptId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereDeletedAt($value)
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_number',
        'client_id',
        'receiver_client_id',
        'type_id',
        'bank_account_id',
        'receiver_country_id',
        'sender_country_id',
        'amount',
        'commission',
        'status_id',
        'receipt_id',
        'note',
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
        'status_id',
        'receipt_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount'     => 'real',
        'commission' => 'real',
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
            $randomNumber = (pow(10, 8) + time() % pow(10, 8));
            $model->transactionNumber = 'WF9' . (string)$randomNumber;
        });
    }

    //<editor-fold desc="Relationships">
    // todo: receiverCountry()
    // todo: senderCountry()

    /**
     * The client that opened the transfer
     *
     * @return BelongsTo
     */
    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * The client that received the transfer
     */
    public function receiverClient() : BelongsTo
    {
        return $this->belongsTo(Client::class, 'receiver_client_id');
    }

    /**
     * Type of the transfer
     *
     * @return BelongsTo
     */
    public function type() : BelongsTo
    {
        return $this->belongsTo(TransferType::class);
    }

    /**
     * Bank account of the receiver client
     *
     * @return BelongsTo
     */
    public function bankAccount() : BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    /**
     * Natures of the transfer
     *
     * @return BelongsToMany
     */
    public function natures() : BelongsToMany
    {
        return $this->belongsToMany(Nature::class, 'transfer_natures');
    }

    /**
     * Current status of the transfer
     *
     * @return BelongsTo
     */
    public function status() : BelongsTo
    {
        return $this->belongsTo(TransferStatus::class);
    }

    /**
     * Receipt of the transfer
     *
     * @return BelongsTo
     */
    public function receipt() : BelongsTo
    {
        return $this->belongsTo(TransferReceipt::class);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
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
