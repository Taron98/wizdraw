<?php

namespace Wizdraw\Models\Pivots;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class GroupClient
 * @package Wizdraw\Models\Pivots
 */
class GroupClient extends AbstractPivot
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'client_id',
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
