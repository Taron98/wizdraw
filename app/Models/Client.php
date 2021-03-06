<?php

namespace Wizdraw\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Wizdraw\Cache\Entities\CountryCache;
use Wizdraw\Cache\Services\CountryCacheService;
use Wizdraw\Models\Pivots\GroupClient;
use Wizdraw\Services\Entities\FacebookUser;

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
 * @property string $state
 * @property string $city
 * @property string $address
 * @property string $clientType
 * @property boolean $didSetup
 * @property boolean $isApproved
 * @property integer $affiliateId
 * @property boolean $isChanged
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property string $dateOfIssue
 * @property string $birthPlace
 * @property-read \Wizdraw\Models\IdentityType $identityType
 * @property-read \Wizdraw\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Group[] $groups
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Group[] $adminGroups
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Transfer[] $transfers
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Transfer[] $receivedTransfers
 * @property-read \Wizdraw\Models\Vip $vip
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\BankAccount[] $bankAccounts
 * @property-read \Wizdraw\Models\Affiliate $affiliate
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $readNotifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
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
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereClientType($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereDidSetup($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereIsApproved($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereAffiliateId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereIsChanged($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereDateOfIssue($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Client whereBirthPlace($value)
 * @mixin \Eloquent
 */
class Client extends AbstractModel implements AuthorizableContract
{
    use SoftDeletes, Authorizable, Notifiable;

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
        'state',
        'city',
        'address',
        'client_type',
        'did_setup',
        'is_approved',
        'affiliate_id',
        'is_changed',
        'deleted_at',
        'date_of_issue',
        'birth_place'
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
        'did_setup'   => 'boolean',
        'is_approved' => 'boolean',
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
            if (!empty($model->identityNumber)) {
                $model->didSetup = 1;
            }
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

    /**
     * Route notifications for the sms channel.
     *
     * @return string
     */
    public function routeNotificationForSms()
    {
        return '+' . preg_replace('/[^0-9]/', '', $this->phone);
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->user->email;
    }

    //<editor-fold desc="Relationships">
    /**
     * One-to-one relationship with identity_types table
     *
     * @return BelongsTo
     */
    public function identityType(): BelongsTo
    {
        return $this->belongsTo(IdentityType::class);
    }

    /**
     * One-to-one relationship with users table
     *
     * @return HasOne
     */
    public function user(): HasOne
    {
        // todo: fix the signup flow, and then remove the latest()
        return $this->hasOne(User::class)
            ->latest();
    }

    /**
     * Many-to-many relationship with group_clients table
     *
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_clients')
            ->withPivot(['is_approved']);
    }

    /**
     * Many-to-many relationship with group_clients table
     *
     * @return HasMany
     */
    public function adminGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'admin_client_id')
            ->with('memberClients');
    }

    /**
     * Transfers that this client has been sent
     *
     * @return HasMany
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class)
            ->with([
                'client',
                'receiverClient',
                'bankAccount',
                'natures',
                'status',
                'receipt',
            ])
            ->latest();
    }

    /**
     * Transfers that this client has been received
     *
     * @return HasMany
     */
    public function receivedTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'receiver_client_id');
    }

    /**
     * The vip number of the client
     *
     * @return HasOne
     */
    public function vip(): HasOne
    {
        return $this->hasOne(Vip::class);
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
        $pivot = null;

        switch (get_class($parent)) {
            case Group::class:
                $pivot = new GroupClient($parent, $attributes, $table, $exists);
                break;

            default:
                $pivot = parent::newPivot($parent, $attributes, $table, $exists);
        }

        return $pivot;
    }

    /**
     * TODO: Currently, we haven't decided yet
     * TODO:  how to implement the caching of the old tables
     */
    public function defaultCountry()
    {

    }

    /**
     * @return int
     */
    public function getDefaultCountry()
    {
        return $this->defaultCountryId;
    }

    /**
     * @return int
     */
    public function getResidentCountry()
    {
        return $this->residentCountryId;
    }

    /**
     * TODO: Currently, we haven't decided yet
     * TODO:  how to implement the caching of the old tables
     */
    public function residentCountry()
    {

    }

    /**
     * Get all clients that sent transfers to this client
     *
     * @return Collection
     */
    public function senders(): Collection
    {
        /** @var Collection $transfers */
        $transfers = $this->receivedTransfers()->with('client')->get();

        return $transfers->map(function ($transfer) {
            /** @var Transfer $transfer */
            return $transfer->client;
        });
    }

    /**
     * Get all clients that received transfers from this client
     *
     * @return Collection
     */
    public function receivers(): Collection
    {
        /** @var Collection $receivedTransfers */
        $receivedTransfers = $this->receivedTransfers()->with('receiverClient')->get();

        return $receivedTransfers->map(function ($receivedTransfer) {
            /** @var Transfer $receivedTransfer */
            return $receivedTransfer->receiverClient;
        });
    }

    /**
     * @return HasMany
     */
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    /**
     * Affiliate Code of the client
     *
     * @return BelongsTo
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
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
        $this->attributes[ 'phone' ] = phone($phone);
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getIdentityExpireAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getBirthDateAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
    }
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getIdentityNumber()
    {
        return $this->identityNumber;
    }

    /**
     * @param string $identityNumber
     */
    public function setIdentityNumber($identityNumber)
    {
        $this->identityNumber = $identityNumber;
    }

    /**
     * @return string
     */
    public function getIdentityExpire()
    {
        return Carbon::parse($this->identityExpire);
    }

    /**
     * @param string $identityExpire
     */
    public function setIdentityExpire($identityExpire)
    {
        $this->identityExpire = $identityExpire;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getBirthDate()
    {
        return Carbon::parse($this->birthDate);
    }

    /**
     * @param string $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * @param string $clientType
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;
    }

    /**
     * @return bool
     */
    public function isDidSetup()
    {
        return $this->didSetup;
    }

    /**
     * @param bool $didSetup
     */
    public function setDidSetup($didSetup)
    {
        $this->didSetup = $didSetup;
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->isApproved;
    }

    /**
     * @param bool $isApproved
     */
    public function setIsApproved($isApproved)
    {
        $this->isApproved = $isApproved;
    }

    /**
     * @return string
     */
    public function getAffiliateId()
    {
        return $this->affiliateId;
    }

    /**
     * @param integer $affiliateId
     */
    public function setAffiliateId($affiliateId)
    {
        $this->affiliateId = $affiliateId;
    }

    /**
     * @return bool
     */
    public function isChanged()
    {
        return $this->is_changed;
    }

    /**
     * @param bool $isChanged
     */
    public function setIsChanged($isChanged)
    {
        $this->isChanged = $isChanged;
    }

    /**
     * @return bool
     */
    public function canTransfer(): bool
    {
        $transfers = $this
            ->transfers
            ->where('status.status', '!=', TransferStatus::STATUS_CANCELLED);

        return !(!$this->isApproved && $transfers->count() > 0);
    }

    /**
     * @param int|null $hour
     * @param int|null $minute
     * @param int|null $second
     *
     * @return Carbon
     */
    public function getTargetTime(int $hour = null, int $minute = null, int $second = null): Carbon
    {
        $targetTime = Carbon::createFromTime($hour, $minute, $second);

        if (is_null($this->defaultCountryId)) {
            return $targetTime;
        }

        /** @var CountryCache $defaultCountry */
        $defaultCountry = resolve(CountryCacheService::class)->find($this->defaultCountryId);

        return $defaultCountry->getLocalTime($targetTime);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $fullName = implode(' ', array_filter([
            $this->firstName,
            $this->middleName,
            $this->lastName,
        ]));

        return $fullName;
    }
    //</editor-fold>

    /**
     * @return string
     */
    public function getDateOfIssue()
    {
        return Carbon::parse($this->dateOfIssue);
    }

    /**
     * @param string $dateOfIssue
     */
    public function setDateOfIssue($dateOfIssue)
    {
        $this->dateOfIssue = $dateOfIssue;
    }

    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * @param integer $birthPlace
     */
    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = $birthPlace;
    }
}
