<?php

namespace Wizdraw\Services;

use Wizdraw\Models\AbstractModel;
use Wizdraw\Repositories\AbstractRepository;

/**
 * Class AbstractService
 * @package Wizdraw\Services
 */
abstract class AbstractService
{

    /** @var  AbstractRepository */
    protected $repository;

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->repository->all($columns);
    }

    /**
     * @param array $data
     *
     * @return AbstractModel
     */
    public function create(array $data) : AbstractModel
    {
        return $this->repository->create($data);
    }

    /**
     * @param array  $data
     * @param mixed  $id
     * @param string $attribute
     *
     * @return AbstractModel
     */
    public function update(array $data, $id, $attribute = "id") : AbstractModel
    {
        $data = array_key_snake_case($data);

        $this->repository->update($data, $id, $attribute);
        $model = $this->repository->find($id);

        return $model;
    }

    /**
     * @param AbstractModel $model
     * @param string        $key
     * @param string        $attribute
     *
     * @return AbstractModel
     */
    public function updateModel(AbstractModel $model, string $key = '', string $attribute = 'id')
    {
        if (empty($key)) {
            $key = $model->getId();
        }

        return $this->update($model->attributesToArray(), $key, $attribute);
    }

}