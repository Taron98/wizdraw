<?php

namespace Wizdraw\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Wizdraw\Traits\EloquentCamelCaseTrait;

/**
 * Wizdraw\Models\AbstractPivot
 *
 * @mixin \Eloquent
 */
abstract class AbstractPivot extends Pivot
{
    use EloquentCamelCaseTrait;

}