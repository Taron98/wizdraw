<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wizdraw\Models\Pivots\GroupClient;

/**
 * Wizdraw\Models\Group
 *
 * @property int $id
 * @property string $name
 * @property int $adminClientId
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Wizdraw\Models\Client $adminClient
 * @property-read \Illuminate\Database\Eloquent\Collection|\Wizdraw\Models\Client[] $memberClients
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereAdminClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Group extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'admin_client_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
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
     * One-to-many relationship with clients table
     *
     * @return BelongsTo
     */
    public function adminClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'admin_client_id');
    }

    /**
     * Many-to-many relationship with clients table
     *
     * @return BelongsToMany
     */
    public function memberClients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'group_clients')
            ->withPivot(['is_approved'])
            ->with(['bankAccounts', 'bankAccounts.bankBranch']);
    }

    /**
     * Create a new pivot model instance
     *
     * @param  Model $parent
     * @param  array $attributes
     * @param  string $table
     * @param  bool $exists
     *
     * @return Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists)
    {
        if ($parent instanceof Client) {
            return new GroupClient($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">

    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
    //</editor-fold>

}
