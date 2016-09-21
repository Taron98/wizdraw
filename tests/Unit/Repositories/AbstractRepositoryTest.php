<?php

namespace Wizdraw\Tests\Unit\Repositories;

use Wizdraw\Repositories\AbstractRepository;
use Wizdraw\Tests\AbstractTestCase;

/**
 * Class AbstractRepositoryTest
 * @package Wizdraw\Tests\Unit\Repositories
 */
abstract class AbstractRepositoryTest extends AbstractTestCase
{

    /** @var  string */
    protected $repositoryClass;

    /** @var  AbstractRepository */
    protected $repository;

    /**
     * Setup the test environment
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->app->make($this->repositoryClass);
    }

}
