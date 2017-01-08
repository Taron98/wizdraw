<?php

namespace Wizdraw\Models;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Wizdraw\Services\Entities\FacebookUser;

/**
 * Wizdraw\Models\User
 *
 * @property integer $id
 * @property integer $clientId
 * @property string $email
 * @property string $password
 * @property string $facebookId
 * @property string $facebookToken
 * @property \Carbon\Carbon $facebookTokenExpire
 * @property string $deviceId
 * @property integer $verifyCode
 * @property \Carbon\Carbon $verifyExpire
 * @property boolean $isPending
 * @property \Carbon\Carbon $passwordChangedAt
 * @property \Carbon\Carbon $lastLoginAt
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Wizdraw\Models\Client $client
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $readNotifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereFacebookId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereFacebookToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereFacebookTokenExpire($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereDeviceId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereVerifyCode($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereVerifyExpire($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereIsPending($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User wherePasswordChangedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereDeletedAt($value)
 * @mixin \Eloquent
 */
class User extends AbstractModel implements
    AuthenticatableContract,
    CanResetPasswordContract
{
    use SoftDeletes, Authenticatable, CanResetPassword, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'email',
        'password',
        'facebook_id',
        'facebook_token',
        'facebook_token_expire',
        'device_id',
        'verify_code',
        'verify_expire',
        'is_pending',
        'password_changed_at',
        'last_login_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_pending' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'facebook_token_expire',
        'verify_expire',
        'password_changed_at',
        'last_login_at',
        'deleted_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        static::creating(function ($model) {
            /** @var User $model */
            $model->generateVerifyCode(true);
        });
    }

    /**
     * Generating a verification for the user, before saving.
     *
     * @param bool $isNew
     */
    public function generateVerifyCode($isNew = false)
    {
        $verifyExpire = config('auth.verification.expire');

        if (empty($this->verifyCode) || !$isNew) {
            $this->verifyCode = $this->password = generate_code();
            $this->verifyExpire = Carbon::now()->addMinutes($verifyExpire);
        }
    }

    /**
     * Convert facebook user entity into user model
     *
     * @param FacebookUser $facebookUser
     *
     * @return User
     */
    public function fromFacebookUser(FacebookUser $facebookUser)
    {
        $tokenExpire = Carbon::createFromTimestamp($facebookUser->getExpire());

        $user = new User();
        $user->email = $facebookUser->getEmail();
        $user->facebookId = $facebookUser->getId();
        $user->facebookToken = $facebookUser->getAccessToken();
        $user->facebookTokenExpire = $tokenExpire;

        return $user;
    }

    /**
     * Route notifications for the pushwoosh channel.
     *
     * @return string
     */
    public function routeNotificationForPushwoosh()
    {
        return $this->deviceId;
    }

    //<editor-fold desc="Relationships">
    /**
     * One-to-one relationship with clients table
     *
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class)
            ->with([
                'vip',
                'affiliate',
            ]);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    /**
     * Hash the password each time the password is being updated
     *
     * @param string $password
     */
    public function setPasswordAttribute(string $password)
    {
        if (Hash::needsRehash($password)) {
            $password = Hash::make($password);
        }

        $this->attributes[ 'password' ] = $password;
        $this->attributes[ 'password_changed_at' ] = Carbon::now();
    }
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId
     */
    public function setFacebookId(string $facebookId)
    {
        $this->facebookId = $facebookId;
    }

    /**
     * @return string
     */
    public function getFacebookToken()
    {
        return $this->facebookToken;
    }

    /**
     * @param string $facebookToken
     */
    public function setFacebookToken(string $facebookToken)
    {
        $this->facebookToken = $facebookToken;
    }

    /**
     * @return Carbon
     */
    public function getFacebookTokenExpire()
    {
        return $this->facebookTokenExpire;
    }

    /**
     * @param Carbon $facebookTokenExpire
     */
    public function setFacebookTokenExpire(Carbon $facebookTokenExpire)
    {
        $this->facebookTokenExpire = $facebookTokenExpire;
    }

    /**
     * @return string
     */
    public function getDeviceId()
    {
        return $this->deviceId;
    }

    /**
     * @param string $deviceId
     */
    public function setDeviceId(string $deviceId)
    {
        $this->deviceId = $deviceId;
    }

    /**
     * @return int
     */
    public function getVerifyCode()
    {
        return $this->verifyCode;
    }

    /**
     * @param int $verifyCode
     */
    public function setVerifyCode($verifyCode)
    {
        $this->verifyCode = $verifyCode;
    }

    /**
     * @return Carbon
     */
    public function getVerifyExpire()
    {
        return $this->verifyExpire;
    }

    /**
     * @param Carbon $verifyExpire
     */
    public function setVerifyExpire($verifyExpire)
    {
        $this->verifyExpire = $verifyExpire;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->isPending;
    }

    /**
     * @param bool $isPending
     */
    public function setIsPending(bool $isPending)
    {
        $this->isPending = $isPending;
    }

    /**
     * @return Carbon
     */
    public function getPasswordChangedAt()
    {
        return $this->passwordChangedAt;
    }

    /**
     * @param Carbon $passwordChangedAt
     */
    public function setPasswordChangedAt(Carbon $passwordChangedAt)
    {
        $this->passwordChangedAt = $passwordChangedAt;
    }

    /**
     * @return Carbon
     */
    public function getLastLoginAt()
    {
        return $this->lastLoginAt;
    }

    /**
     * @param Carbon $lastLoginAt
     */
    public function setLastLoginAt(Carbon $lastLoginAt)
    {
        $this->lastLoginAt = $lastLoginAt;
    }

    /**
     * @return bool
     */
    public function hasNoPassword()
    {
        return Hash::check($this->verifyCode, $this->password);
    }
    //</editor-fold>

}
