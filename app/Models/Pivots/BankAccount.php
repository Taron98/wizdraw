<?php

namespace Wizdraw\Models\Pivots;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BankAccount
 * @package Wizdraw\Models\Pivots
 */
class BankAccount extends AbstractPivot
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
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * @param string $accountNumber
     */
    public function setIsApproved($accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }
    //</editor-fold>

}
