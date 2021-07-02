<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Wizdraw\Models\FirebaseToken
 *
 * @property string $fcmToken
 * @property string $deviceId
 * @property integer $clientId
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\FirebaseToken whereFcmToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\FirebaseToken whereDeviceId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\FirebaseToken whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\FirebaseToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\FirebaseToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FirebaseToken extends Model
{
    /**
     * @var string
     */
    protected $table = "firebase_token";
    protected $primaryKey = 'device_id';

    /**
     * @var array
     */
    protected $fillable = [ 'device_id',  'fcm_token', 'client_id', 'created_at', 'updated_at'];
}
