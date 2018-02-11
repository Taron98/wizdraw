<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\IdentityType
 *
 * @property int $id
 * @property string $type
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon|null $updatedAt
 * @property \Carbon\Carbon|null $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Client[] $clients
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\IdentityType onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\IdentityType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\IdentityType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\IdentityType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\IdentityType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\IdentityType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\IdentityType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\IdentityType withoutTrashed()
 * @mixin \Eloquent
 */
class IdentityType extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'identity_types';

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
     * One-to-many relationship with clients table
     *
     * @return HasMany
     */
    public function clients(): HasMany
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
