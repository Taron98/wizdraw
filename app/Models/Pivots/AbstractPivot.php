<?php

namespace Wizdraw\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Wizdraw\Traits\ModelCamelCaseTrait;

/**
 * Wizdraw\Models\AbstractPivot
 *
 * @mixin \Eloquent
 */
abstract class AbstractPivot extends Pivot
{
    use ModelCamelCaseTrait;

}