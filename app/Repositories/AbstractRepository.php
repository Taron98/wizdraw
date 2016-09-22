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
     * @param AbstractModel $model
     *
     * @return AbstractModel
     */
    public function updateModel(AbstractModel $model) : AbstractModel
    {
        return $this->update($model->attributesToArray(), $model->getId());
    }

}