<?php

namespace Wizdraw\Models;

use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Wizdraw\Traits\ModelCamelCaseTrait;

/**
 * Wizdraw\Models\User
 *
 * @property integer                     $id
 * @property integer                     $clientId
 * @property string                      $email
 * @property string                      $username
 * @property string                      $password
 * @property string                      $facebookId
 * @property string                      $facebookToken
 * @property Carbon                      $facebookTokenExpire
 * @property string                      $deviceId
 * @property integer                     $verifyCode
 * @property Carbon                      $verifyExpire
 * @property boolean                     $isPending
 * @property Carbon                      $passwordChangedAt
 * @property Carbon                      $lastLoginAt
 * @property Carbon                      $createdAt
 * @property Carbon                      $updatedAt
 * @property Carbon                      $deletedAt
 * @property-read \Wizdraw\Models\Client $client
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereUsername($value)
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
    AuthorizableContract,
    CanResetPasswordContract
{
    use SoftDeletes, ModelCamelCaseTrait, Authenticatable, Authorizable, CanResetPassword;

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
        'username',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'isPending' => 'boolean',
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
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * One-to-one relationship with clients table
     *
     * @return BelongsTo
     */
    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Registering event before creating a new user
     */
    protected static function boot()
    {
        static::creating(function ($model) {
            $model->generateVerifyCode();
        });
    }

    /**
     * Generating a verification for the user, before saving.
     */
    protected function generateVerifyCode()
    {
        if (empty($this->attributes[ 'verify_code' ])) {
            $this->attributes[ 'verify_code' ] = generate_code();
        }
    }

    /**
     * Hash the password each time the password is being updated
     *
     * @param string $password
     */
    public function setPasswordAttribute(string $password)
    {
        $this->attributes[ 'password' ] = Hash::make($password);
    }

    //<editor-fold desc="Getters & Setters">
    /**
     * @return int
     */
    public function getClientId() : int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     */
    public function setClientId(int $clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword() : string
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
     * @return string
     */
    public function getFacebookId() : string
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
    public function getFacebookToken() : string
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
    public function getFacebookTokenExpire() : Carbon
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
    public function getDeviceId() : string
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
    public function getVerifyCode() : int
    {
        return $this->verifyCode;
    }

    /**
     * @param int $verifyCode
     */
    public function setVerifyCode(int $verifyCode)
    {
        $this->verifyCode = $verifyCode;
    }

    /**
     * @return Carbon
     */
    public function getVerifyExpire() : Carbon
    {
        return $this->verifyExpire;
    }

    /**
     * @param Carbon $verifyExpire
     */
    public function setVerifyExpire(Carbon $verifyExpire)
    {
        $this->verifyExpire = $verifyExpire;
    }

    /**
     * @return boolean
     */
    public function isIsPending() : bool
    {
        return $this->isPending;
    }

    /**
     * @param boolean $isPending
     */
    public function setIsPending(bool $isPending)
    {
        $this->isPending = $isPending;
    }

    /**
     * @return Carbon
     */
    public function getPasswordChangedAt() : Carbon
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
    public function getLastLoginAt() : Carbon
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
    //</editor-fold>

}
