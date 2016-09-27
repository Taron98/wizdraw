<?php

namespace Wizdraw\Tests\Unit\Repositories;

use ClientsTableSeeder;
use IdentityTypesTableSeeder;
use Wizdraw\Repositories\GroupRepository;

/**
 * Class GroupRepositoryRepositoryTest
 * @package Wizdraw\Tests\Unit\Repositories
 */
class GroupRepositoryRepositoryTest extends AbstractRepositoryTest
{

    /** @var  string */
    protected $repositoryClass = GroupRepository::class;

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
