<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wizdraw\Traits\ModelCamelCaseTrait;

/**
 * Wizdraw\Models\IdentityType
 *
 * @property integer                                                                $id
 * @property string                                                                 $type
 * @property \Carbon\Carbon                                                         $createdAt
 * @property \Carbon\Carbon                                                         $updatedAt
 * @property \Carbon\Carbon                                                         $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Client[] $clients
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\IdentityType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\IdentityType whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\IdentityType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\IdentityType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\IdentityType whereDeletedAt($value)
 * @mixin \Eloquent
 */
class IdentityType extends AbstractModel
{
    use SoftDeletes, ModelCamelCaseTrait;

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
    ];

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

    /**
     * One-to-many relationship with clients table
     *
     * @return HasMany
     */
    public function clients() : HasMany
    {
        return $this->hasMany(Client::class);
    }

}
