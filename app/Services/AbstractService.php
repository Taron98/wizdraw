<?php

namespace Wizdraw\Services;

use Exception;
use Illuminate\Support\Facades\Log;
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
     * @param array $data
     * @param mixed $id
     *
     * @return AbstractModel|bool
     */
    public function update(array $data, $id)
    {
        try {
            $model = $this->repository->update($data, $id);
        } catch (Exception $exception) {
            Log::info($exception);
            return null;
        }

        return $model;
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * @param AbstractModel $model
     * @param string $key
     *
     * @return AbstractModel
     */
    public function updateModel(AbstractModel $model, string $key = '')
    {
        if (empty($key)) {
            $key = $model->getId();
        }

        return $this->update($model->attributesToArray(), $key);
    }

}