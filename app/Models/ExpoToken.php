<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Model;

class ExpoToken extends Model
{
    /**
     * @var string
     */
    protected $table = "expo_token";

    /**
     * @var array
     */
    protected $fillable = [ 'device_id',  'expo_token', 'created_at', 'updated_at'];
}
