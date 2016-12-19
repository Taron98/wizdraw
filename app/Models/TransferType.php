<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\TransferType
 *
 * @property int $id
 * @property string $type
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Transfer[] $transfers
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferType whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\TransferType whereDeletedAt($value)
 * @mixin \Eloquent
 */
class TransferType extends AbstractModel
{
    use SoftDeletes;

    const TYPE_PICKUP_CASH = 'PICKUP_CASH';
    const TYPE_DEPOSIT = 'DEPOSIT';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transfer_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
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
     * Transfers that belongs to this type
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
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }
    //</editor-fold>

}
