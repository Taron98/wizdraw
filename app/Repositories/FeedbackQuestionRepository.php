<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\FeedbackQuestion;

/**
 * Class FeedbackQuestionRepository
 * @package Wizdraw\Repositories
 */
class FeedbackQuestionRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return FeedbackQuestion::class;
    }

}