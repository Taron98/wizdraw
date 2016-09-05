<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wizdraw\Traits\ModelCamelCaseTrait;

/**
 * Wizdraw\Models\Client
 *
 * @property integer                           $id
 * @property integer                           $identityTypeId
 * @property string                            $identityNumber
 * @property string                            $identityExpire
 * @property string                            $firstName
 * @property string                            $middleName
 * @property string                            $lastName
 * @property string                            $birthDate
 * @property string                            $gender
 * @property string                            $phone
 * @property integer                           $residentCountryId
 * @property string                            $city
 * @property string                            $address
 * @property string                            $clientType
 * @property \Carbon\Carbon                    $createdAt
 * @property \Carbon\Carbon                    $updatedAt
 * @property \Carbon\Carbon                    $deletedAt
 * @property-read \Wizdraw\Models\IdentityType $IdentityType
 * @property-read \Wizdraw\Models\User         $User
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereIdentityTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereIdentityNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereIdentityExpire($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereMiddleName($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereBirthDate($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereResidentCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereClientType($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Client extends AbstractModel
{
    use SoftDeletes, ModelCamelCaseTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identityTypeId',
        'identityNumber',
        'identityExpire',
        'firstName',
        'middleName',
        'lastName',
        'birthDate',
        'gender',
        'phone',
        'residentCountryId',
        'city',
        'address',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'createdAt',
        'updatedAt',
        'deletedAt',
    ];

    public static $snakeAttributes = false;

    /**
     * One-to-many relationship with identity_types table
     *
     * @return BelongsTo
     */
    public function identityType() : BelongsTo
    {
        return $this->belongsTo(IdentityType::class);
    }

    /**
     * One-to-one relationship with users table
     *
     * @return HasOne
     */
    public function user() : HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * TODO: Currently, we haven't decided yet
     * TODO:  how to implement the caching of the old tables
     */
    public function residentCountry()
    {

    }

}
