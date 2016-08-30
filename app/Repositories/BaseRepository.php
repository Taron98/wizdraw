<?php

namespace Wizdraw\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use Wizdraw\Models\BaseModel;

/**
 * Class BaseRepository
 * @package Wizdraw\Repositories
 */
abstract class BaseRepository extends Repository
{

    /**
     * Shorthand for updating a model, instead of array
     *
     * @param BaseModel $model
     * @param string    $key
     * @param string    $attribute
     *
     * @return mixed
     */
    public function updateModel(BaseModel $model, string $key, string $attribute = "id")
    {
        return $this->update($model->toArray(), $key, $attribute);
    }

}