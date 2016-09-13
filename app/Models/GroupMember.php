<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wizdraw\Traits\ModelCamelCaseTrait;

/**
 * Wizdraw\Models\GroupMember
 *
 * @property integer                     $id
 * @property integer                     $groupId
 * @property integer                     $memberClientId
 * @property boolean                     $isApproved
 * @property \Carbon\Carbon              $createdAt
 * @property \Carbon\Carbon              $updatedAt
 * @property \Carbon\Carbon              $deletedAt
 * @property-read \Wizdraw\Models\Group  $group
 * @property-read \Wizdraw\Models\Client $client
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\GroupMember whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\GroupMember whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\GroupMember whereMemberClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\GroupMember whereIsApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\GroupMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\GroupMember whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\GroupMember whereDeletedAt($value)
 * @mixin \Eloquent
 */
class GroupMember extends Model
{
    use SoftDeletes, ModelCamelCaseTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'member_client_id',
        'is_approved',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_approved' => 'boolean',
    ];

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
     * One-to-one relationship with group table
     *
     * @return BelongsTo
     */
    public function group() : BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * One-to-many relationship with group table
     *
     * @return BelongsTo
     */
    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">

    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return boolean
     */
    public function isIsApproved(): bool
    {
        return $this->isApproved;
    }

    /**
     * @param boolean $isApproved
     */
    public function setIsApproved(bool $isApproved)
    {
        $this->isApproved = $isApproved;
    }
    //</editor-fold>

}
