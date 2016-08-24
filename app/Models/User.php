<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Wizdraw\Models\User
 *
 * @property integer $id
 * @property integer $clientId
 * @property string $username
 * @property string $password
 * @property string $facebookToken
 * @property string $deviceId
 * @property string $lastLoginAt
 * @property boolean $isPending
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property string $deletedAt
 * @property-read \Wizdraw\Models\Client $Client
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereFacebookToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereDeviceId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereIsPending($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\User whereDeletedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use SoftDeletes;

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
        'username',
        'password',
        'facebook_token',
        'device_id',
        'last_login_at',
        'is_pending'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_pending' => 'boolean'
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

}
