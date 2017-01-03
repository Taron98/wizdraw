<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\Vip
 *
 * @property integer $id
 * @property integer $credits
 * @property integer $clientId
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Wizdraw\Models\Client $client
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereCredits($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Vip extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'credits',
        'client_id',
        'deleted_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'client_id',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'credits' => 'integer',
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
     * Perform a model insert operation.
     * This override comes to avoid saving a vip number that already in use in the back office.
     * Used numbers: 9606153, 9665643, 9710371
     *
     * @param  Builder $query
     *
     * @return bool
     */
    protected function performInsert(Builder $query)
    {
        $performInsert = parent::performInsert($query);

        // If this is the used vip number, delete and recreate
        if ($performInsert && ($this->id === 106153 || $this->id === 165643 || $this->id === 210371)) {
            $this->forceDelete();
            $this->setId(0);

            return parent::performInsert($query);
        }

        return $performInsert;
    }

    //<editor-fold desc="Relationships">
    /**
     * The client that own that vip number
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
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     *
     * @return Vip
     */
    public function setClientId($clientId): Vip
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * @param int $credits
     *
     * @return Vip
     */
    public function setCredits($credits): Vip
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * @return int
     */
    public function getVipNumber(): int
    {
        // The application vip numbers starts from 9500000
        return 9500000 + $this->id;
    }
    //</editor-fold>

}
