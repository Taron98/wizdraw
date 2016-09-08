<?php

namespace Wizdraw\Services;

use Wizdraw\Repositories\IdentityTypeRepository;

/**
 * Class IdentityTypeService
 * @package Wizdraw\Services
 */
class IdentityTypeService extends AbstractService
{

    /**
     * IdentityTypeService constructor.
     *
     * @param IdentityTypeRepository $identityTypeRepository
     */
    public function __construct(IdentityTypeRepository $identityTypeRepository)
    {
        $this->repository = $identityTypeRepository;
    }

}