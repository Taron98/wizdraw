<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\Nature
 *
 * @property int $id
 * @property string $nature
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Transfer[] $transfers
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Nature whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Nature whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Nature whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Nature whereNature($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Nature whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Nature extends AbstractModel
{
    use SoftDeletes;

    const NATURE_SUPPORT_OR_GIFT = 'Support / Gift for relative';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'natures';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nature',
        'deleted_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    //<editor-fold desc="Relationships">
    /**
     * Many-to-many relationship with transfer_natures table
     *
     * @return BelongsToMany
     */
    public function transfers(): BelongsToMany
    {
        return $this->belongsToMany(Transfer::class, 'transfer_natures');
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getNature(): string
    {
        return $this->nature;
    }

    /**
     * @param string $nature
     */
    public function setNature(string $nature)
    {
        $this->nature = $nature;
    }
    //</editor-fold>

}
