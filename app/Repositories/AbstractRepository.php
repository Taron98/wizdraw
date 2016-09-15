<?php

namespace Wizdraw\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
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
    public function exists(array $attributes) : bool
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
    public function createModel(AbstractModel $model)
    {
        return $this->create($model->attributesToArray());
    }

    /**
     * Save a model without massive assignment
     *
     * @param array  $data
     * @param        $id
     * @param string $attribute
     *
     * @return bool
     */
    public function update(array $data, $id, $attribute = "id") : bool
    {
        $data[ 'exists' ] = true;

        if (empty($data[ $attribute ])) {
            $data[ $attribute ] = $id;
        }

        return parent::saveModel($data);
    }

    /**
     * @param AbstractModel $model
     * @param string        $key
     * @param string        $attribute
     *
     * @return bool
     */
    public function updateModel(AbstractModel $model, string $key = '', string $attribute = 'id') : bool
    {
        if (empty($key)) {
            $key = $model->getId();
        }

        return $this->update($model->attributesToArray(), $key, $attribute);
    }

}