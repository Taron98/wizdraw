<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Wizdraw\Models\ExpoToken
 *
 * @mixin \Eloquent
 */
class ExpoToken extends Model
{
    /**
     * @var string
     */
    protected $table = "expo_token";

    /**
     * @var array
     */
    protected $fillable = [ 'device_id',  'expo_token', 'client_id', 'created_at', 'updated_at'];
}
