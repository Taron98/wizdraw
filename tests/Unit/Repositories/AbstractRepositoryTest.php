<?php

namespace Wizdraw\Tests\Unit\Repositories;

use Wizdraw\Models\AbstractModel;
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

    /** @test */
    public function it_can_create_entity()
    {
        /** @var Group $entity */
        $entity = factory($this->repository->model())->make();

        $expected = $this->repository->create($entity->attributesToArray());

        $this->seeInDatabase(
            $entity->getTable(),
            [
                'id' => $expected->getId(),
            ]
        );
    }

    /**
     * @test
     *
     * @param array $excludeAttributes
     */
    public function it_can_update_entity($excludeAttributes = [])
    {
        $defaultExcludedAttributes = [
            'createdAt',
            'updatedAt',
            'deletedAt',
        ];

        /** @var AbstractModel $original */
        $original = factory($this->repository->model())->create();

        /** @var AbstractModel $expected */
        $expected = factory($this->repository->model())->make();
        $expected->setId($original->getId());

        $this->repository->update($expected->toArray(), $original->getId());

        $this->seeInDatabase(
            $original->getTable(),
            $expected->attributesToArray(
                array_merge($defaultExcludedAttributes, $excludeAttributes)
            )
        );
    }

}
