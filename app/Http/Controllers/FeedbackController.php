<?php

namespace Wizdraw\Http\Controllers;

use Wizdraw\Http\Requests\Feedback\FeedbackReviewRequest;
use Wizdraw\Services\FeedbackService;

/**
 * Class FeedbackController
 * @package Wizdraw\Http\Controllers
 */
class FeedbackController extends AbstractController
{

    /** @var FeedbackService */
    private $feedbackService;

    /**
     * FeedbackController constructor.
     *
     * @param FeedbackService $feedbackService
     */
    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    /**
     * @return mixed
     */
    public function questions()
    {
        return $this->feedbackService->questions();
    }

    /**
     * @param FeedbackReviewRequest $request
     *
     * @return mixed
     */
    public function review(FeedbackReviewRequest $request)
    {
        $client = $request->user()->client;
        $inputs = $request->inputs();

        $review = $this->feedbackService->createReview($client, $inputs);

        return $this->respond($review);
    }

}
