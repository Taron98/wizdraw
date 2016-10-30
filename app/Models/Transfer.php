<?php

namespace Wizdraw\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

/**
 * Wizdraw\Models\Transfer
 *
 * @property integer $id
 * @property string $transactionNumber
 * @property integer $clientId
 * @property integer $receiverClientId
 * @property integer $bankAccountId
 * @property integer $receiverCountryId
 * @property integer $senderCountryId
 * @property integer $statusId
 * @property float $amount
 * @property float $commission
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Wizdraw\Models\Client $client
 * @property-read \Wizdraw\Models\Client $receiverClient
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Nature[] $natures
 * @property-read \Wizdraw\Models\Status $status
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereTransactionNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereReceiverClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereBankAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereReceiverCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereSenderCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereStatusId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereAmount($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Transfer whereCommission($value)
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
        'bank_account_id',
        'receiver_country_id',
        'sender_country_id',
        'amount',
        'commission',
        'status_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'client_id',
        'receiver_client_id',
        'status_id',
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
            $randomNumber = mt_rand(pow(10, 10), pow(10, 11) - 1);
            $model->transactionNumber = 'WF9' . (string)$randomNumber;
        });
    }

    //<editor-fold desc="Relationships">
    // todo: bankBranch()
    // todo: bankAccount()
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
        return $this->belongsTo(Status::class);
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
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    /**
     * @return string
     */
    public function getTransactionNumber(): string
    {
        return $this->transactionNumber;
    }

    /**
     * @param string $transactionNumber
     *
     * @return Transfer
     */
    public function setTransactionNumber(string $transactionNumber): Transfer
    {
        $this->transactionNumber = $transactionNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getBankAccountId(): int
    {
        return $this->bankAccountId;
    }

    /**
     * @param int $bankAccountId
     *
     * @return Transfer
     */
    public function setBankAccountId(int $bankAccountId): Transfer
    {
        $this->bankAccountId = $bankAccountId;

        return $this;
    }

    /**
     * @return int
     */
    public function getReceiverCountryId(): int
    {
        return $this->receiverCountryId;
    }

    /**
     * @param int $receiverCountryId
     *
     * @return Transfer
     */
    public function setReceiverCountryId(int $receiverCountryId): Transfer
    {
        $this->receiverCountryId = $receiverCountryId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSenderCountryId(): int
    {
        return $this->senderCountryId;
    }

    /**
     * @param int $senderCountryId
     *
     * @return Transfer
     */
    public function setSenderCountryId(int $senderCountryId): Transfer
    {
        $this->senderCountryId = $senderCountryId;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * @param int $statusId
     *
     * @return Transfer
     */
    public function setStatusId(int $statusId): Transfer
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
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
    public function setCommission(float $commission): Transfer
    {
        $this->commission = $commission;

        return $this;
    }
    //</editor-fold>

}
