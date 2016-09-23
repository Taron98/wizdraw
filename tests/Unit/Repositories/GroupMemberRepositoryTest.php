<?php

namespace Wizdraw\Tests\Unit\Repositories;

use ClientsTableSeeder;
use GroupsTableSeeder;
use IdentityTypesTableSeeder;
use Wizdraw\Repositories\GroupMemberRepository;

/**
 * Class GroupMemberRepositoryTest
 * @package Wizdraw\Tests\Unit\Repositories
 */
class GroupMemberRepositoryTest extends AbstractRepositoryTest
{

    /** @var  string */
    protected $repositoryClass = GroupMemberRepository::class;

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
        $this->seed(GroupsTableSeeder::class);
    }

}
