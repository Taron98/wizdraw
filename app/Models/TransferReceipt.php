<?php

namespace Wizdraw\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\TransferReceipt
 *
 * @property integer $id
 * @property string $image
 * @property string $number
 * @property string $expense
 * @property string $expenseType
 * @property string $remark
 * @property \Carbon\Carbon $issuedAt
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Transfer[] $transfers
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereExpense($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereExpenseType($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereRemark($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereIssuedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferReceipt whereDeletedAt($value)
 * @mixin \Eloquent
 */
class TransferReceipt extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfer_receipts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'number',
        'expense',
        'expense_type',
        'remark',
        'issued_at',
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
        'issued_at',
        'deleted_at',
    ];

    //<editor-fold desc="Relationships">
    /**
     * Transfers currently in this status
     *
     * @return HasMany
     */
    public function transfers() : HasMany
    {
        return $this->hasMany(Transfer::class);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return TransferReceipt
     */
    public function setImage($image): TransferReceipt
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @return TransferReceipt
     */
    public function setNumber($number): TransferReceipt
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpense()
    {
        return $this->expense;
    }

    /**
     * @param string $expense
     *
     * @return TransferReceipt
     */
    public function setExpense($expense): TransferReceipt
    {
        $this->expense = $expense;

        return $this;
    }

    /**
     * @return string
     */
    public function getExpenseType()
    {
        return $this->expenseType;
    }

    /**
     * @param string $expenseType
     *
     * @return TransferReceipt
     */
    public function setExpenseType($expenseType): TransferReceipt
    {
        $this->expenseType = $expenseType;

        return $this;
    }

    /**
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param string $remark
     *
     * @return TransferReceipt
     */
    public function setRemark($remark): TransferReceipt
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * @return Carbon
     */
    public function getIssuedAt()
    {
        return $this->issuedAt;
    }

    /**
     * @param Carbon $issuedAt
     *
     * @return TransferReceipt
     */
    public function setIssuedAt($issuedAt): TransferReceipt
    {
        $this->issuedAt = $issuedAt;

        return $this;
    }
    //</editor-fold>

}
