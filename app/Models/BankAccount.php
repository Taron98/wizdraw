<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\BankAccount
 *
 * @property integer $id
 * @property integer $bankId
 * @property integer $bankBranchId
 * @property integer $clientId
 * @property string $accountNumber
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Transfer[] $transfers
 * @property-read \Wizdraw\Models\BankBranch $bankBranch
 * @property-read \Wizdraw\Models\Client $client
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankAccount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankAccount whereBankId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankAccount whereBankBranchId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankAccount whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankAccount whereAccountNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankAccount whereDeletedAt($value)
 * @mixin \Eloquent
 */
class BankAccount extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_id',
        'bank_branch_id',
        'client_id',
        'account_number',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    //<editor-fold desc="Relationships">
    // todo: bank()

    /**
     * Transfers that used this bank account
     *
     * @return HasMany
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class);
    }

    /**
     * Bank branch where the account exists
     *
     * @return BelongsTo
     */
    public function bankBranch(): BelongsTo
    {
        return $this->belongsTo(BankBranch::class);
    }

    /**
     * Owner client of the account
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return int
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * @param int $bankId
     *
     * @return BankAccount
     */
    public function setBankId($bankId): BankAccount
    {
        $this->bankId = $bankId;

        return $this;
    }

    /**
     * @return int
     */
    public function getBankBranchId()
    {
        return $this->bankBranchId;
    }

    /**
     * @param int $bankBranchId
     *
     * @return BankAccount
     */
    public function setBankBranchId($bankBranchId): BankAccount
    {
        $this->bankBranchId = $bankBranchId;

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
     * @return BankAccount
     */
    public function setClientId($clientId): BankAccount
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string $accountNumber
     *
     * @return BankAccount
     */
    public function setAccountNumber($accountNumber): BankAccount
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }
    //</editor-fold>

}
