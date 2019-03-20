<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\BankBranch
 *
 * @property integer $id
 * @property integer $bankBranchId
 * @property string $name
 * @property string $address
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankBranch whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankBranch whereBankBranchId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankBranch whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankBranch whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankBranch whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankBranch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\BankBranch whereDeletedAt($value)
 * @mixin \Eloquent
 */
class BankBranch extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_branches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_branch_id',
        'name',
        'address',
        'deleted_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
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
    // todo: bankBranch()
    // todo: bankAccounts()
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }
    //</editor-fold>

}
