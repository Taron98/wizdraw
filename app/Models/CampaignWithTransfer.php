<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Wizdraw\Models\CampaignWithTransfer
 *
 * @property integer $id
 * @property integer $campaignId
 * @property integer $transferId
 * @property string $transactionNumber
 * @property \Carbon\Carbon $createdAt
 * @property-read \Wizdraw\Models\Campaign $campaign
 * @property-read \Wizdraw\Models\Transfer $transfer
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\CampaignWithTransfer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\CampaignWithTransfer whereCampaignId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\CampaignWithTransfer whereTransferId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\CampaignWithTransfer whereTransactionNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\CampaignWithTransfer whereCreatedAt($value)
 * @mixin \Eloquent
 */
class CampaignWithTransfer extends AbstractModel
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'campaigns_with_transfers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_id',
        'transaction_number',
        'created_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'campaign_id' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * @return BelongsTo
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    /**
     * @return BelongsTo
     */
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class, 'transaction_number');
    }

    /**
     * @param $campaignId
     * @param $transferId
     * @param $transactionNumber
     * @return bool
     */
    public function insertToCampaignsWithTransfers($campaignId, $transferId, $transactionNumber)
    {
        return $this->insert([
            ['campaign_id' => $campaignId, 'transfer_id' => $transferId, 'transaction_number' => $transactionNumber]
        ]);
    }


}
