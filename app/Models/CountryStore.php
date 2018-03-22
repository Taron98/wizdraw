<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/21/2017
 * Time: 11:44
 */

namespace Wizdraw\Models;

/**
 * Wizdraw\Models\CountryStore
 *
 * @property int $id
 * @property int $countryId
 * @property string $store
 * @property int $active
 * @property int $useQrCode
 * @property string $csNumber
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\CountryStore whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\CountryStore whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\CountryStore whereCsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\CountryStore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\CountryStore whereStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Wizdraw\Models\CountryStore whereUseQrCode($value)
 * @mixin \Eloquent
 */
class CountryStore extends AbstractModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'countries_stores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id',
        'store',
        'active',
        'use_qr_code',
        'cs_number'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];
}