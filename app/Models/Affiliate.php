<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\Affiliate
 *
 * @property integer $id
 * @property string $code
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Client[] $clients
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Affiliate whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Affiliate whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Affiliate whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Affiliate whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Affiliate extends AbstractModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'affiliates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    //<editor-fold desc="Relationships">
    /**
     * one-to-many relationship with clients table
     *
     * @return hasMany
     */
    public function clients() : HasMany
    {
        return $this->hasMany(Client::class, 'clients');
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Affiliate
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
    //</editor-fold>

}
