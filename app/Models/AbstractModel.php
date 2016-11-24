<?php

namespace Wizdraw\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Wizdraw\Traits\EloquentCamelCaseTrait;

/**
 * Wizdraw\Models\AbstractModel
 *
 * @mixin \Eloquent
 */
abstract class AbstractModel extends Model
{
    use EloquentCamelCaseTrait;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @return Carbon
     */
    public function getDeletedAt(): Carbon
    {
        return $this->deletedAt;
    }

}