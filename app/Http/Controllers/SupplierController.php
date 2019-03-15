<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 15.03.2019
 * Time: 11:42
 */

namespace Wizdraw\Http\Controllers;
use Wizdraw\Models\Supplier;

class SupplierController

{
    public function __construct()
    {
    }

    /**
     * Showing list of suppliers for the given country
     * @param $country_id
     */
    public function suppliers($country_id)
    {
        $supplier = new Supplier();
        $suppliers = $supplier->getSuppliers($country_id);
        return $suppliers;
    }
}