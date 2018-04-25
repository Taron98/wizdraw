<?php

namespace Wizdraw\Models;

/**
 * Wizdraw\Models\Campaign
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
     * @param $id
     * @return $this
     */
    public function getCampaignById($id)
    {
        return $this->where('id', '=', $id)->get();
    }

}
