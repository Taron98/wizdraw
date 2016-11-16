<?php

namespace Wizdraw\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;

/**
 * Class TransferPolicy
 * @package Wizdraw\Policies
 */
class TransferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can show the transfer
     *
     * @param Client $client
     * @param Transfer $transfer
     *
     * @return bool
     */
    public function show(Client $client, Transfer $transfer)
    {
        return ($client->getId() === $transfer->client->getId() ||
            $client->getId() === $transfer->receiverClient->getId());
    }

    /**
     * Determine whether the user can add a receipt to the transfer
     *
     * @param Client $client
     * @param Transfer $transfer
     *
     * @return bool
     */
    public function addReceipt(Client $client, Transfer $transfer)
    {
        return ($client->getId() === $transfer->client->getId());
    }

    /**
     * Determine whether the user can add feedback to the transfer
     *
     * @param Client $client
     * @param Transfer $transfer
     *
     * @return bool
     */
    public function feedback(Client $client, Transfer $transfer)
    {
        return ($client->getId() === $transfer->client->getId());
    }

    /**
     * Determine whether the user can abort the transfer
     *
     * @param Client $client
     * @param Transfer $transfer
     *
     * @return bool
     */
    public function abort(Client $client, Transfer $transfer)
    {
        return ($client->getId() === $transfer->client->getId());
    }

}
