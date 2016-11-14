<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Feedback;

/**
 * Class FeedbackRepository
 * @package Wizdraw\Repositories
 */
class FeedbackRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return Feedback::class;
    }

}