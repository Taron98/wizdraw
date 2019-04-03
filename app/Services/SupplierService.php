<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 03.04.2019
 * Time: 13:10
 */

namespace Wizdraw\Services;
use Wizdraw\Repositories\SupplierRepository;

/**
 * Class AbstractService
 * @package Wizdraw\Services
 */
class SupplierService extends AbstractService
{
    /**
     * SupplierService constructor.
     *
     * @param SupplierRepository $supplierRepository
     */
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->repository = $supplierRepository;
    }

    /**
     * @param $country
     *
     * @return mixed
     */
    public function findByCountry($country)
    {
        return $this->repository->findByCountry($country);
    }
}