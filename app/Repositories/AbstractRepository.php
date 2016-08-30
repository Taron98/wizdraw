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