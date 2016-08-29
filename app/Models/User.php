<?php

namespace Wizdraw\Models;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Wizdraw\Models\Traits\CamelCaseTrait;

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
 * @property \Carbon\Carbon              $facebookTokenExpire
 * @property string                      $deviceId
 * @property boolean                     $isPending
 * @property \Carbon\Carbon              $lastLoginAt
 * @property \Carbon\Carbon              $createdAt
 * @property \Carbon\Carbon              $updatedAt
 * @property \Carbon\Carbon              $deletedAt
 * @property-read \Wizdraw\Models\Client $Client
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereFacebookId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereFacebookToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereFacebookTokenExpire($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereDeviceId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereIsPending($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereDeletedAt($value)
 * @mixin \Eloquent
 */
class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use SoftDeletes, CamelCaseTrait, Authenticatable, Authorizable, CanResetPassword;

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
        'last_login_at',
        'is_pending',
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
        'created_at',
        'updated_at',
        'deleted_at',
        'last_login_at',
    ];

    /**
     * One-to-one relationship with clients table
     *
     * @return BelongsTo
     */
    public function Client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
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

}
