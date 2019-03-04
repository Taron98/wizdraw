<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Wizdraw\Models\Campaign
 *
 * @property integer $id
 * @property string $campaignName
 * @property integer $originCountryId
 * @property boolean $active
 * @property \Carbon\Carbon $startDate
 * @property \Carbon\Carbon $endDate
 * @property \Carbon\Carbon $createdAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\CampaignWithTransfer[] $campaignWithTransfer
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Campaign whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Campaign whereCampaignName($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Campaign whereOriginCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Campaign whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Campaign whereStartDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Campaign whereEndDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Campaign whereCreatedAt($value)
 * @mixin \Eloquent
 */

class Campaign extends AbstractModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaigns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'campaign_name',
        'origin_country_id',
        'active',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'origin_country_id' => 'integer',
        'active' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'end_date',
    ];

    /**
     * @return HasMany
     */
    public function campaignWithTransfer(): HasMany
    {
        return $this->hasMany(CampaignWithTransfer::class);
    }

    /**
     * @param $id
     * @return $this
     */
    public function getCampaignById($id)
    {
        return $this->where('id', '=', $id)->get();
    }

}
