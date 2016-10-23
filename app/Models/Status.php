<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\Status
 *
 * @property integer $id
 * @property string $status
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Transfer[] $transfers
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Status whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Status whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Status whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Status whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Status extends AbstractModel
{
    use SoftDeletes;

    const STATUS_ABORTED = 'ABORTED';
    const STATUS_WAIT = 'WAIT';
    const STATUS_WAIT_FOR_PROCESS_OBLIGO = 'WAIT FOR PROCESS(Obligo)';
    const STATUS_WAIT_FOR_PROCESS = 'WAIT FOR PROCESS';
    const STATUS_WAIT_FOR_PROCESS_COMPLIANCE = 'WAIT FOR PROCESS(Compliance)';
    const STATUS_CHECK_DOCUMENTS = 'CHECK DOCUMENTS';
    const STATUS_PENDING = 'PENDING';
    const STATUS_POSTED = 'POSTED';
    const STATUS_FAILED = 'FAILED';
    const STATUS_CHANGE = 'CHANGE';
    const STATUS_REQUEST_CANCEL = 'REQUEST CANCEL';
    const STATUS_FOR_VERIFICATION = 'FOR VERIFICATION';
    const STATUS_CONFIRMED = 'CONFIRMED';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
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
    //</editor-fold>

}
