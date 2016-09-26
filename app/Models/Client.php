<?php

namespace Wizdraw\Models;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Wizdraw\Models\Pivots\GroupClient;
use Wizdraw\Services\Entities\FacebookUser;
use Wizdraw\Traits\ModelCamelCaseTrait;

/**
 * Wizdraw\Models\Client
 *
 * @property integer $id
 * @property integer $identityTypeId
 * @property string $identityNumber
 * @property \Carbon\Carbon $identityExpire
 * @property string $firstName
 * @property string $middleName
 * @property string $lastName
 * @property \Carbon\Carbon $birthDate
 * @property string $gender
 * @property string $phone
 * @property integer $defaultCountryId
 * @property integer $residentCountryId
 * @property string $city
 * @property string $address
 * @property string $clientType
 * @property boolean $didSetup
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Wizdraw\Models\IdentityType $identityType
 * @property-read \Wizdraw\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Group[] $groups
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
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereDefaultCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereResidentCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereClientType($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereDidSetup($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereDeletedAt($value)
 * @mixin \Eloquent
 */
class Client extends AbstractModel implements AuthorizableContract
{
    use SoftDeletes, Authorizable, ModelCamelCaseTrait;

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
        'identity_type_id',
        'identity_number',
        'identity_expire',
        'first_name',
        'middle_name',
        'last_name',
        'birth_date',
        'gender',
        'phone',
        'default_country_id',
        'resident_country_id',
        'city',
        'address',
        'client_type',
        'did_setup',
        'deleted_at',
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
        'did_setup' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'identity_expire',
        'birth_date',
        'deleted_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::updating(function ($model) {
            /** @var Client $model */
            $model->didSetup = true;
        });
    }

    /**
     * Convert facebook user entity into client model
     *
     * @param FacebookUser $facebookUser
     *
     * @return Client
     */
    public function fromFacebookUser(FacebookUser $facebookUser)
    {
        $client = new Client();
        $client->firstName = $facebookUser->getFirstName();
        $client->middleName = $facebookUser->getMiddleName();
        $client->lastName = $facebookUser->getLastName();
        $client->gender = $facebookUser->getGender();
        $client->birthDate = $facebookUser->getBirthday();

        return $client;
    }

    //<editor-fold desc="Relationships">
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
     * Many-to-many relationship with group_clients table
     *
     * @return BelongsToMany
     */
    public function groups() : BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_clients')
            ->withPivot(['is_approved']);
    }

    /**
     * Create a new pivot model instance
     *
     * @param  Model $parent
     * @param  array $attributes
     * @param  string $table
     * @param  bool $exists
     *
     * @return Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        if ($parent instanceof Group) {
            return new GroupClient($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }

    /**
     * TODO: Currently, we haven't decided yet
     * TODO:  how to implement the caching of the old tables
     */
    public function defaultCountry()
    {

    }

    /**
     * TODO: Currently, we haven't decided yet
     * TODO:  how to implement the caching of the old tables
     */
    public function residentCountry()
    {

    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    /**
     * Format the phone every time is being updated
     *
     * @param string $phone
     */
    public function setPhoneAttribute(string $phone)
    {
        $this->attributes[ 'phone' ] = phone_format($phone);
    }
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getIdentityNumber() : string
    {
        return $this->identityNumber;
    }

    /**
     * @param string $identityNumber
     */
    public function setIdentityNumber(string $identityNumber)
    {
        $this->identityNumber = $identityNumber;
    }

    /**
     * @return string
     */
    public function getIdentityExpire() : string
    {
        return $this->identityExpire;
    }

    /**
     * @param string $identityExpire
     */
    public function setIdentityExpire(string $identityExpire)
    {
        $this->identityExpire = $identityExpire;
    }

    /**
     * @return string
     */
    public function getFirstName() : string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getMiddleName() : string
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     */
    public function setMiddleName(string $middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * @return string
     */
    public function getLastName() : string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getBirthDate() : string
    {
        return $this->birthDate;
    }

    /**
     * @param string $birthDate
     */
    public function setBirthDate(string $birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getGender() : string
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getPhone() : string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getCity() : string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getAddress() : string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getClientType() : string
    {
        return $this->clientType;
    }

    /**
     * @param string $clientType
     */
    public function setClientType(string $clientType)
    {
        $this->clientType = $clientType;
    }

    /**
     * @return boolean
     */
    public function isDidSetup() : bool
    {
        return $this->didSetup;
    }

    /**
     * @param boolean $didSetup
     */
    public function setDidSetup(bool $didSetup)
    {
        $this->didSetup = $didSetup;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return "{$this->firstName} {$this->middleName} {$this->lastName}";
    }
    //</editor-fold>

}
