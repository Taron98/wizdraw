<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wizdraw\Traits\ModelCamelCaseTrait;

/**
 * Wizdraw\Models\Group
 *
 * @property integer                                                                $id
 * @property string                                                                 $name
 * @property integer                                                                $adminClientId
 * @property \Carbon\Carbon                                                         $createdAt
 * @property \Carbon\Carbon                                                         $updatedAt
 * @property \Carbon\Carbon                                                         $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Client[] $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Client[] $members
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereAdminClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Group extends AbstractModel
{
    use SoftDeletes, ModelCamelCaseTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'admin_client_id',
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
     * One-to-many relationship with clients table
     *
     * @return BelongsTo
     */
    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class, 'admin_client_id');
    }

    /**
     * One-to-many relationship with clients table
     *
     * @return HasMany
     */
    public function members() : HasMany
    {
        return $this->hasMany(Client::class);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">

    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
    //</editor-fold>

}
