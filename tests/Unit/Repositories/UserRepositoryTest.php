<?php

namespace Wizdraw\Tests\Unit\Repositories;

use ClientsTableSeeder;
use IdentityTypesTableSeeder;
use Wizdraw\Repositories\UserRepository;

/**
 * Class UserRepositoryTest
 * @package Wizdraw\Tests\Unit\Repositories
 */
class UserRepositoryTest extends AbstractRepositoryTest
{

    /** @var  string */
    protected $repositoryClass = UserRepository::class;

    /**
     * Setup the test environment
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->seed(IdentityTypesTableSeeder::class);
        $this->seed(ClientsTableSeeder::class);
    }

}
