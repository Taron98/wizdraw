<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Wizdraw\Models\ExpoToken
 *
 * @property string $expoToken
 * @property string $deviceId
 * @property integer $clientId
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\ExpoToken whereExpoToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\ExpoToken whereDeviceId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\ExpoToken whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\ExpoToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\ExpoToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ExpoToken extends Model
{
    /**
     * @var string
     */
    protected $table = "expo_token";
    protected $primaryKey = 'device_id';

    /**
     * @var array
     */
    protected $fillable = [ 'device_id',  'expo_token', 'client_id', 'created_at', 'updated_at'];
}
