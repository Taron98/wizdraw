<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\TransferStatus
 *
 * @property int $id
 * @property string $status
 * @property string $originalStatus
 * @property string $color
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon|null $updatedAt
 * @property \Carbon\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Transfer[] $transfers
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferStatus onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\TransferStatus whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\TransferStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\TransferStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\TransferStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\TransferStatus whereOriginalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\TransferStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\TransferStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferStatus withoutTrashed()
 * @mixin \Eloquent
 */
class TransferStatus extends AbstractModel
{
    use SoftDeletes;

    const STATUS_ABORTED = 'ABORTED';
    const STATUS_POSTED = 'Posted';
    const STATUS_PENDING_FOR_PAYMENT_AT_7_ELEVEN = 'Pending for Payment at 7-Eleven';
    const STATUS_PENDING_FOR_PAYMENT_AT_CIRCLE_K = 'Pending for Payment at Circle-K';
    const STATUS_PENDING_FOR_PAYMENT_AT_PAY_TO_AGENT = 'Pending for Payment at Pay-To-Agent';
    const STATUS_WAIT_FOR_PROCESS = 'WAIT FOR PROCESS';
    const STATUS_ON_HOLD = 'On hold';
    const STATUS_CHECK_DOCUMENTS = 'CHECK DOCUMENTS';
    const STATUS_PENDING = 'Pending';
    const STATUS_AWAITING_WITHDRAWAL = 'Awaiting withdrawal';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_REQUEST_AMENDMENT = 'Request amendment';
    const STATUS_REQUEST_CANCEL = 'REQUEST CANCEL';
    const STATUS_FOR_VERIFICATION = 'FOR VERIFICATION';
    const STATUS_COMPLETED = 'Completed';
    const STATUS_WAIT = 'Posted wizdraw';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfer_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'original_status',
        'color',
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
    /**
     * Transfers currently in this status
     *
     * @return HasMany
     */
    public function transfers(): HasMany
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getOriginalStatus(): string
    {
        return $this->originalStatus;
    }

    /**
     * @param string $originalStatus
     */
    public function setOriginalStatus(string $originalStatus)
    {
        $this->originalStatus = $originalStatus;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return TransferStatus
     */
    public function setColor(string $color): TransferStatus
    {
        $this->color = $color;

        return $this;
    }
    //</editor-fold>

}
