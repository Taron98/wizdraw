<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 14.03.2019
 * Time: 18:44
 */

namespace Wizdraw\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * Wizdraw\Models\Supplier
 *
 * @property integer $id
 * @property string $supplierName
 * @property string $supplierNameWfs
 * @property string $countryId
 * @property string $active
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Supplier whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Supplier whereSupplierName($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Supplier whereSupplierNameWfs($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Supplier whereCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Supplier whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Supplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Supplier extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_name',
        'supplier_name_wfs',
        'active',
        'country_id'
    ];

    public function getSuppliers($country_id){
        return $this->where('country_id', $country_id)->where('active', '1')->get();
    }
}