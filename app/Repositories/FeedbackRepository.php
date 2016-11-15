<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Client;
use Wizdraw\Models\Feedback;
use Wizdraw\Models\Transfer;

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

    /**
     * @param Client $client
     * @param Transfer $transfer
     * @param array $attributes
     *
     * @return Feedback
     */
    public function createWithRelation(Client $client, Transfer $transfer, array $attributes)
    {
        /** @var Feedback $newFeedback */
        $newFeedback = $this->makeModel()->fill($attributes);

        $newFeedback
            ->transfer()->associate($transfer)
            ->client()->associate($client);

        $newFeedback->save();

        return (is_null($newFeedback)) ?: $newFeedback;
    }

    /**
     * @param int $clientId
     * @param int $transferId
     *
     * @return bool
     */
    public function alreadyFeedbacked(int $clientId, int $transferId) : bool
    {
        return $this->exists(['client_id' => $clientId, 'transfer_id' => $transferId]);
    }

}