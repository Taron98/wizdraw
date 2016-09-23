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

    /** @var array */
    private $defaultExcludedOnUpdate = [
        'createdAt',
        'updatedAt',
        'deletedAt',
    ];

    /** @var  string */
    protected $repositoryClass;

    /** @var  AbstractRepository */
    protected $repository;

    /** @var array */
    protected $excludeOnUpdate = [];

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
     * todo: keep it dry
     */
    public function it_can_update_entity()
    {
        /** @var AbstractModel $original */
        $original = factory($this->repository->model())->create();

        /** @var AbstractModel $expected */
        $expected = factory($this->repository->model())->make();
        $expected->setId($original->getId());

        $this->repository->update($expected->toArray(), $original->getId());

        $this->seeInDatabase(
            $original->getTable(),
            $expected->attributesToArray(
                array_merge($this->defaultExcludedOnUpdate, $this->excludeOnUpdate)
            )
        );
    }

    /**
     * @test
     *
     * todo: keep it dry
     */
    public function it_can_update_model_entity()
    {
        /** @var AbstractModel $original */
        $original = factory($this->repository->model())->create();

        /** @var AbstractModel $expected */
        $expected = factory($this->repository->model())->make();
        $expected->setId($original->getId());

        $this->repository->updateModel($expected);

        $this->seeInDatabase(
            $original->getTable(),
            $expected->attributesToArray(
                array_merge($this->defaultExcludedOnUpdate, $this->excludeOnUpdate)
            )
        );
    }

    /** @test */
    public function it_can_delete_entity()
    {
        /** @var AbstractModel $original */
        $original = factory($this->repository->model())->create();

        $this->repository->delete($original->getId());

        $this->notSeeInDatabase(
            $original->getTable(),
            [
                'id'        => $original->getId(),
                'deletedAt' => null,
            ]
        );
    }

}
