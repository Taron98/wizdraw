<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Wizdraw\Models\FirebaseToken
 *
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
