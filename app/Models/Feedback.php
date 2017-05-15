<?php

namespace Wizdraw\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Wizdraw\Models\Feedback
 *
 * @property int $id
 * @property int $transferId
 * @property int $clientId
 * @property int $feedbackQuestionId
 * @property int $rating
 * @property string $note
 * @property \Carbon\Carbon $createdAt
 * @property \Carbon\Carbon $updatedAt
 * @property \Carbon\Carbon $deletedAt
 * @property-read \Wizdraw\Models\Client $client
 * @property-read \Wizdraw\Models\FeedbackQuestion $feedbackQuestion
 * @property-read \Wizdraw\Models\Transfer $transfer
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereFeedbackQuestionId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereNote($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereTransferId($value)
 * @method static \Illuminate\Database\Query\Builder|\Wizdraw\Models\Feedback whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Feedback extends AbstractModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transfer_id',
        'client_id',
        'feedback_question_id',
        'rating',
        'note',
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
     * @return BelongsTo
     */
    public function transfer(): BelongsTo
    {
        return $this->belongsTo(Transfer::class);
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return BelongsTo
     */
    public function feedbackQuestion(): BelongsTo
    {
        return $this->belongsTo(FeedbackQuestion::class);
    }
    //</editor-fold>

    //<editor-fold desc="Accessors & Mutators">
    //</editor-fold>

    //<editor-fold desc="Getters & Setters">
    /**
     * @return int
     */
    public function getTransferId()
    {
        return $this->transferId;
    }

    /**
     * @param int $transferId
     *
     * @return Feedback
     */
    public function setTransferId($transferId): Feedback
    {
        $this->transferId = $transferId;

        return $this;
    }

    /**
     * @return int
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     *
     * @return Feedback
     */
    public function setClientId($clientId): Feedback
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @return int
     */
    public function getFeedbackQuestionId()
    {
        return $this->feedbackQuestionId;
    }

    /**
     * @param int $feedbackQuestionId
     *
     * @return Feedback
     */
    public function setFeedbackQuestionId($feedbackQuestionId): Feedback
    {
        $this->feedbackQuestionId = $feedbackQuestionId;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     *
     * @return Feedback
     */
    public function setNote($note): Feedback
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     *
     * @return Feedback
     */
    public function setRating($rating): Feedback
    {
        $this->rating = $rating;

        return $this;
    }
    //</editor-fold>

}
