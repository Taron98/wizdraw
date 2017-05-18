<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\Vip
 *
 * @property int $id
 * @property int $number
 * @property int $credits
 * @property int $clientId
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Wizdraw\Models\Client $client
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereCredits($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Vip whereUpdatedAt($value)
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
        'number',
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
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        Vip::observe(VipObserver::class);
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
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param int $number
     *
     * @return Vip
     */
    public function setNumber($number): Vip
    {
        $this->number = $number;

        return $this;
    }
    //</editor-fold>

}
