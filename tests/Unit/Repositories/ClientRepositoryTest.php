<?php

namespace Wizdraw\Tests\Unit\Repositories;

use IdentityTypesTableSeeder;
use Wizdraw\Repositories\ClientRepository;

/**
 * Class ClientRepositoryTest
 * @package Wizdraw\Tests\Unit\Repositories
 */
class ClientRepositoryTest extends AbstractRepositoryTest
{

    /** @var  string */
    protected $repositoryClass = ClientRepository::class;

    /** @var array */
    protected $excludeOnUpdate = [
        'didSetup'
    ];

    /**
     * Setup the test environment
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->seed(IdentityTypesTableSeeder::class);
    }

}
