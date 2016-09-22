<?php

namespace Wizdraw\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Wizdraw\Models\AbstractModel;

/**
 * Class AbstractRepository
 * @package Wizdraw\Repositories
 */
abstract class AbstractRepository extends BaseRepository
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
     *
     * @return AbstractModel
     */
    public function update(array $data, $id) : AbstractModel
    {
        $data[ 'exists' ] = true;

        return parent::update($data, $id);
    }

    /**
     * @param AbstractModel $model
     * @param string        $key
     *
     * @return bool
     */
    public function updateModel(AbstractModel $model, string $key = '') : bool
    {
        if (empty($key)) {
            $key = $model->getId();
        }

        return $this->update($model->attributesToArray(), $key);
    }

}