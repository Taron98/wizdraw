<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 15.03.2019
 * Time: 11:42
 */

namespace Wizdraw\Http\Controllers;
use Wizdraw\Models\Supplier;
use Wizdraw\Services\SupplierService;
use Wizdraw\Services\UserService;

class SupplierController

{
    /** @var  SupplierService */
    private $supplierService;

    /**
     * SupplierController constructor.
     *
     * @param SupplierService $supplierService
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * Showing list of suppliers for the given country
     * @param $country_id
     */
    public function suppliers($country_id)
    {
        return $this->supplierService->findByCountry($country_id);
    }
}