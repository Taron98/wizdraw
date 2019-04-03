<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 03.04.2019
 * Time: 13:11
 */

namespace Wizdraw\Repositories;
use Wizdraw\Models\Supplier;


/**
 * Class SupplierRepository
 * @package Wizdraw\Repositories
 */
class SupplierRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Supplier::class;
    }

}