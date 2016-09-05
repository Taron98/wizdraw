<?php

namespace Wizdraw\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use Illuminate\Database\Eloquent\Model;
use Wizdraw\Models\AbstractModel;

/**
 * Class AbstractRepository
 * @package Wizdraw\Repositories
 */
abstract class AbstractRepository extends Repository
{

    /** @var  AbstractModel */
    protected $model;

    /**
     * @param array $attributes
     *
     * @return bool
     */
    public function exists(array $attributes): bool
    {
        return $this->model->where($attributes)->exists();
    }

    /**
     * Shorthand for creating a model, instead of array
     *
     * @param AbstractModel $model
     *
     * @return mixed
     */
    public function createModel(AbstractModel $model) : mixed
    {
        return $this->create($model->toArray());
    }

    /**
     * Shorthand for updating a model, instead of array
     *
     * @param AbstractModel $model
     * @param string        $key
     * @param string        $attribute
     *
     * @return mixed
     */
    public function updateModel(AbstractModel $model, string $key, string $attribute = "id")
    {
        return $this->update($model->toArray(), $key, $attribute);
    }

}