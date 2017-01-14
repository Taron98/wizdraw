<?php

namespace Wizdraw\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;
use Wizdraw\Repositories\FeedbackQuestionRepository;
use Wizdraw\Repositories\FeedbackRepository;

/**
 * Class FeedbackService
 * @package Wizdraw\Services
 */
class FeedbackService extends AbstractService
{

    /** @var FeedbackQuestionRepository */
    private $feedbackQuestionRepository;

    /**
     * FeedbackService constructor.
     *
     * @param FeedbackRepository $feedbackRepository
     * @param FeedbackQuestionRepository $feedbackQuestionRepository
     */
    public function __construct(
        FeedbackRepository $feedbackRepository,
        FeedbackQuestionRepository $feedbackQuestionRepository
    ) {
        $this->repository = $feedbackRepository;
        $this->feedbackQuestionRepository = $feedbackQuestionRepository;
    }

    /**
     * @return mixed
     */
    public function questions()
    {
        return $this->feedbackQuestionRepository->paginate();
    }

    /**
     * @param int $feedbackQuestionId
     *
     * @return bool
     */
    public function questionExists(int $feedbackQuestionId): bool
    {
        try {
            $feedbackQuestion = $this->feedbackQuestionRepository->find($feedbackQuestionId);
        } catch (ModelNotFoundException $exception) {
            return false;
        }

        return true;
    }

    /**
     * @param Client $client
     * @param Transfer $transfer
     * @param array $attributes
     *
     * @return mixed
     */
    public function createFeedback(Client $client, Transfer $transfer, array $attributes)
    {
        $feedback = $this->repository->createWithRelation($client, $transfer, $attributes);

        return $feedback;
    }

    /**
     * @param Client $client
     * @param Transfer $transfer
     *
     * @return bool
     */
    public function alreadyFeedbacked(Client $client, Transfer $transfer): bool
    {
        return $this->repository->alreadyFeedbacked($client->getId(), $transfer->getId());
    }

    /**
     * @param Client $client
     * @param array $attributes
     *
     * @return mixed
     */
    public function createReview(Client $client, array $attributes)
    {
        $review = $this->repository->createWithRelation($client, null, $attributes);

        return $review;
    }

}